<?php

namespace App\Http\Controllers;

use App\Models\Collaboration;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaborationController extends Controller
{
    public function index()
    {
        $collaborations = Collaboration::with(['project.user', 'participants'])
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                    ->orWhereHas('participants', function ($q) {
                        $q->where('user_id', Auth::id());
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $openCollaborations = Collaboration::with('project.user')
            ->where('status', 'open')
            ->where('user_id', '!=', Auth::id())
            ->whereDoesntHave('participants', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('collaboration.index', compact('collaborations', 'openCollaborations'));
    }

    public function create()
    {
        $projects = Auth::user()->projects()->where('is_public', true)->get();
        return view('collaboration.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'required_skills' => 'required|array|min:1',
            'required_skills.*' => 'string|max:50',
            'team_size' => 'required|integer|min:1|max:20',
            'timeline' => 'required|string|in:week,month,quarter,flexible',
            'is_paid' => 'boolean',
            'budget' => 'nullable|numeric|min:0',
            'budget_type' => 'nullable|in:hourly,fixed,bounty'
        ]);

        // Check if user owns the project
        $project = Project::findOrFail($validated['project_id']);
        if ($project->user_id !== Auth::id()) {
            return back()->with('error', 'You can only create collaborations for your own projects.');
        }

        $collaboration = Collaboration::create([
            'user_id' => Auth::id(),
            'project_id' => $validated['project_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'required_skills' => $validated['required_skills'],
            'team_size' => $validated['team_size'],
            'current_size' => 1, // Creator is automatically a participant
            'timeline' => $validated['timeline'],
            'is_paid' => $validated['is_paid'] ?? false,
            'budget' => $validated['budget'],
            'budget_type' => $validated['budget_type'],
            'status' => 'open'
        ]);

        // Add creator as participant
        $collaboration->participants()->attach(Auth::id(), [
            'role' => 'creator',
            'joined_at' => now()
        ]);

        return redirect()->route('collaboration.show', $collaboration)
            ->with('success', 'Collaboration created successfully!');
    }

    public function show(Collaboration $collaboration)
    {
        $collaboration->load(['project.user', 'participants.profile', 'applications.user']);

        $hasApplied = $collaboration->applications()
            ->where('user_id', Auth::id())
            ->exists();

        $isParticipant = $collaboration->participants()
            ->where('user_id', Auth::id())
            ->exists();

        $canJoin = !$isParticipant &&
            !$hasApplied &&
            $collaboration->status === 'open' &&
            $collaboration->current_size < $collaboration->team_size;

        return view('collaboration.show', compact('collaboration', 'hasApplied', 'isParticipant', 'canJoin'));
    }

    public function join(Collaboration $collaboration)
    {
        if ($collaboration->status !== 'open') {
            return back()->with('error', 'This collaboration is not open for joining.');
        }

        if ($collaboration->current_size >= $collaboration->team_size) {
            return back()->with('error', 'This collaboration is full.');
        }

        $isParticipant = $collaboration->participants()
            ->where('user_id', Auth::id())
            ->exists();

        if ($isParticipant) {
            return back()->with('error', 'You are already a participant.');
        }

        $hasApplied = $collaboration->applications()
            ->where('user_id', Auth::id())
            ->exists();

        if ($hasApplied) {
            return back()->with('error', 'You have already applied to join.');
        }

        // Create application
        $collaboration->applications()->create([
            'user_id' => Auth::id(),
            'message' => 'I would like to join this collaboration.',
            'status' => 'pending'
        ]);

        // Notify collaboration creator
        $collaboration->user->notifications()->create([
            'type' => 'collaboration_application',
            'data' => [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'collaboration_id' => $collaboration->id,
                'collaboration_title' => $collaboration->title,
                'message' => Auth::user()->name . ' wants to join your collaboration'
            ]
        ]);

        return back()->with('success', 'Application sent successfully!');
    }

    public function leave(Collaboration $collaboration)
    {
        $isParticipant = $collaboration->participants()
            ->where('user_id', Auth::id())
            ->exists();

        if (!$isParticipant) {
            return back()->with('error', 'You are not a participant.');
        }

        if ($collaboration->user_id === Auth::id()) {
            return back()->with('error', 'Creator cannot leave the collaboration. You can delete it instead.');
        }

        $collaboration->participants()->detach(Auth::id());
        $collaboration->decrement('current_size');

        return back()->with('success', 'You have left the collaboration.');
    }

    public function markComplete(Collaboration $collaboration)
    {
        if ($collaboration->user_id !== Auth::id()) {
            return back()->with('error', 'Only the creator can mark the collaboration as complete.');
        }

        $collaboration->update(['status' => 'completed']);

        // Notify all participants
        foreach ($collaboration->participants as $participant) {
            if ($participant->id !== Auth::id()) {
                $participant->notifications()->create([
                    'type' => 'collaboration_completed',
                    'data' => [
                        'collaboration_id' => $collaboration->id,
                        'collaboration_title' => $collaboration->title,
                        'message' => 'The collaboration "' . $collaboration->title . '" has been marked as completed'
                    ]
                ]);
            }
        }

        return back()->with('success', 'Collaboration marked as completed!');
    }
}
