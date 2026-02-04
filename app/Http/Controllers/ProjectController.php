<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('user.profile')
            ->where('is_public', true);

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by technology
        if ($request->has('technology')) {
            $query->whereJsonContains('technologies', $request->technology);
        }

        // Filter by difficulty
        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'popular':
                $query->popular();
                break;
            case 'trending':
                $query->where('created_at', '>=', now()->subDays(30))
                    ->popular();
                break;
            default:
                $query->recent();
        }

        $projects = $query->paginate(12);
        $categories = Project::select('category')->distinct()->pluck('category');
        $technologies = $this->getPopularTechnologies();

        return view('projects.index', compact('projects', 'categories', 'technologies'));
    }

    public function create()
    {
        $technologies = $this->getAllTechnologies();
        return view('projects.create', compact('technologies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'short_description' => 'required|string|max:300',
            'description' => 'required|string|max:5000',
            'repository_url' => 'nullable|url',
            'live_url' => 'nullable|url',
            'technologies' => 'required|array|min:1',
            'technologies.*' => 'string|max:50',
            'category' => 'required|string|max:50',
            'difficulty' => 'required|in:beginner,intermediate,advanced,expert',
            'is_public' => 'boolean',
            'thumbnail' => 'nullable|image|max:2048',
            'screenshots' => 'nullable|array|max:5',
            'screenshots.*' => 'image|max:2048'
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('projects/thumbnails', 'public');
            $validated['thumbnail_path'] = $path;
        }

        // Handle screenshots upload
        $screenshots = [];
        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $screenshot) {
                $path = $screenshot->store('projects/screenshots', 'public');
                $screenshots[] = $path;
            }
        }

        $project = Auth::user()->projects()->create(array_merge($validated, [
            'user_id' => Auth::id(),
            'is_public' => $validated['is_public'] ?? true,
            'status' => 'active',
            'screenshots' => $screenshots
        ]));

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        if (!$project->is_public && $project->user_id !== Auth::id()) {
            abort(403, 'This project is private.');
        }

        $project->load(['user.profile', 'contributors.profile', 'forks.user', 'likes.user']);
        $project->incrementViews();

        $relatedProjects = Project::where('category', $project->category)
            ->where('id', '!=', $project->id)
            ->where('is_public', true)
            ->with('user.profile')
            ->limit(4)
            ->get();

        $isLiked = $project->isLikedBy(Auth::user());
        $isContributor = $project->isContributor(Auth::user());

        return view('projects.show', compact('project', 'relatedProjects', 'isLiked', 'isContributor'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $technologies = $this->getAllTechnologies();

        return view('projects.edit', compact('project', 'technologies'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'short_description' => 'required|string|max:300',
            'description' => 'required|string|max:5000',
            'repository_url' => 'nullable|url',
            'live_url' => 'nullable|url',
            'technologies' => 'required|array|min:1',
            'technologies.*' => 'string|max:50',
            'category' => 'required|string|max:50',
            'difficulty' => 'required|in:beginner,intermediate,advanced,expert',
            'is_public' => 'boolean',
            'thumbnail' => 'nullable|image|max:2048',
            'remove_thumbnail' => 'boolean',
            'screenshots' => 'nullable|array|max:5',
            'screenshots.*' => 'image|max:2048',
            'remove_screenshots' => 'nullable|array'
        ]);

        // Handle thumbnail removal/update
        if ($request->has('remove_thumbnail') && $project->thumbnail_path) {
            Storage::disk('public')->delete($project->thumbnail_path);
            $validated['thumbnail_path'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($project->thumbnail_path) {
                Storage::disk('public')->delete($project->thumbnail_path);
            }
            $path = $request->file('thumbnail')->store('projects/thumbnails', 'public');
            $validated['thumbnail_path'] = $path;
        }

        // Handle screenshots
        $currentScreenshots = $project->screenshots ?? [];

        // Remove selected screenshots
        if ($request->has('remove_screenshots')) {
            foreach ($request->remove_screenshots as $screenshot) {
                Storage::disk('public')->delete($screenshot);
                $currentScreenshots = array_diff($currentScreenshots, [$screenshot]);
            }
        }

        // Add new screenshots
        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $screenshot) {
                $path = $screenshot->store('projects/screenshots', 'public');
                $currentScreenshots[] = $path;
            }
        }

        $validated['screenshots'] = array_slice($currentScreenshots, 0, 5);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        // Delete associated files
        if ($project->thumbnail_path) {
            Storage::disk('public')->delete($project->thumbnail_path);
        }

        if ($project->screenshots) {
            foreach ($project->screenshots as $screenshot) {
                Storage::disk('public')->delete($screenshot);
            }
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }

    public function toggleLike(Project $project)
    {
        $user = Auth::user();
        $liked = $project->likes()->where('user_id', $user->id)->first();

        if ($liked) {
            $liked->delete();
            $project->decrementLikes();
            return response()->json(['liked' => false, 'likes_count' => $project->likes_count]);
        } else {
            $project->likes()->create(['user_id' => $user->id]);
            $project->incrementLikes();
            return response()->json(['liked' => true, 'likes_count' => $project->likes_count]);
        }
    }

    public function fork(Project $project)
    {
        if (!$project->is_public) {
            return back()->with('error', 'Cannot fork private projects.');
        }

        $forkedProject = $project->fork(Auth::user());

        return redirect()->route('projects.show', $forkedProject)
            ->with('success', 'Project forked successfully!');
    }

    public function requestCollaboration(Project $project, Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'role' => 'required|string|max:100'
        ]);

        // Create collaboration request
        $project->collaborations()->create([
            'user_id' => Auth::id(),
            'title' => 'Collaboration Request: ' . $project->title,
            'description' => $request->message,
            'required_skills' => $project->technologies,
            'team_size' => 2,
            'current_size' => 1,
            'timeline' => 'flexible',
            'status' => 'pending'
        ]);

        // Notify project owner
        $project->user->notifications()->create([
            'type' => 'collaboration_request',
            'data' => [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'project_id' => $project->id,
                'project_title' => $project->title,
                'message' => Auth::user()->name . ' wants to collaborate on your project'
            ]
        ]);

        return back()->with('success', 'Collaboration request sent!');
    }

    private function getPopularTechnologies()
    {
        return Project::where('is_public', true)
            ->select('technologies')
            ->get()
            ->pluck('technologies')
            ->flatten()
            ->countBy()
            ->sortDesc()
            ->take(20)
            ->keys()
            ->toArray();
    }

    private function getAllTechnologies()
    {
        return [
            'JavaScript',
            'TypeScript',
            'Python',
            'Java',
            'PHP',
            'C#',
            'C++',
            'Ruby',
            'Go',
            'Rust',
            'React',
            'Vue.js',
            'Angular',
            'Svelte',
            'Next.js',
            'Nuxt.js',
            'Node.js',
            'Express',
            'Django',
            'Flask',
            'Laravel',
            'Spring',
            'Ruby on Rails',
            '.NET',
            'FastAPI',
            'MongoDB',
            'PostgreSQL',
            'MySQL',
            'Redis',
            'Firebase',
            'Supabase',
            'Tailwind CSS',
            'Bootstrap',
            'Material-UI',
            'Styled Components',
            'Docker',
            'Kubernetes',
            'AWS',
            'Azure',
            'Google Cloud',
            'DigitalOcean',
            'GraphQL',
            'REST API',
            'WebSockets',
            'WebRTC',
            'TensorFlow',
            'PyTorch',
            'Scikit-learn',
            'Pandas',
            'NumPy'
        ];
    }
}
