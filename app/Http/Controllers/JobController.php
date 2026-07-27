<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::query()->where('is_active', true);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('required_skills', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($locationType = $request->input('location_type')) {
            $query->where('location_type', $locationType);
        }

        if ($experienceLevel = $request->input('experience_level')) {
            $query->where('experience_level', $experienceLevel);
        }

        $sortField = $request->input('sort', 'created_at');
        $sortDir = $request->input('direction', 'desc');
        $allowedSorts = ['created_at', 'salary_min', 'salary_max', 'title', 'company_name'];
        if (! in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        $jobs = $query->orderBy($sortField, $sortDir)->paginate(12)->withQueryString();

        $types = ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship'];
        $locations = ['Remote', 'On-site', 'Hybrid'];
        $levels = ['Entry', 'Junior', 'Mid', 'Senior', 'Lead', 'Executive'];

        return view('jobs.index', compact('jobs', 'types', 'locations', 'levels'));
    }

    public function create()
    {
        $types = ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship'];
        $locationTypes = ['Remote', 'On-site', 'Hybrid'];
        $experienceLevels = ['Entry', 'Junior', 'Mid', 'Senior', 'Lead', 'Executive'];

        return view('jobs.create', compact('types', 'locationTypes', 'experienceLevels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:Full-time,Part-time,Contract,Freelance,Internship',
            'location_type' => 'required|in:Remote,On-site,Hybrid',
            'location' => 'nullable|string|max:255',
            'required_skills' => 'nullable|string',
            'company_website' => 'nullable|url|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_currency' => 'nullable|string|max:10',
            'experience_level' => 'required|in:Entry,Junior,Mid,Senior,Lead,Executive',
            'is_featured' => 'sometimes|boolean',
        ]);

        $data['user_id'] = Auth::id();
        $data['salary_currency'] = $data['salary_currency'] ?? 'NPR';
        $data['is_active'] = true;
        $data['is_featured'] = $request->boolean('is_featured') && Auth::user()->isAdmin();

        $data['required_skills'] = ! empty($data['required_skills'])
            ? array_values(array_filter(array_map('trim', explode(',', $data['required_skills']))))
            : [];

        Job::create($data);

        return redirect()->route('jobs.index')->with('success', 'Job posted successfully!');
    }

    public function show(Job $job)
    {
        $job->increment('views_count');

        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own job listings.');
        }

        $types = ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship'];
        $locationTypes = ['Remote', 'On-site', 'Hybrid'];
        $experienceLevels = ['Entry', 'Junior', 'Mid', 'Senior', 'Lead', 'Executive'];

        return view('jobs.edit', compact('job', 'types', 'locationTypes', 'experienceLevels'));
    }

    public function update(Request $request, Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            abort(403, 'You can only update your own job listings.');
        }

        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:Full-time,Part-time,Contract,Freelance,Internship',
            'location_type' => 'required|in:Remote,On-site,Hybrid',
            'location' => 'nullable|string|max:255',
            'required_skills' => 'nullable|string',
            'company_website' => 'nullable|url|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_currency' => 'nullable|string|max:10',
            'experience_level' => 'required|in:Entry,Junior,Mid,Senior,Lead,Executive',
            'is_featured' => 'sometimes|boolean',
        ]);

        $data['salary_currency'] = $data['salary_currency'] ?? 'NPR';
        $data['is_featured'] = $request->boolean('is_featured') && Auth::user()->isAdmin();

        $data['required_skills'] = ! empty($data['required_skills'])
            ? array_values(array_filter(array_map('trim', explode(',', $data['required_skills']))))
            : [];

        $job->update($data);

        return redirect()->route('jobs.show', $job)->with('success', 'Job updated successfully!');
    }

    public function destroy(Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own job listings.');
        }

        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully!');
    }
}
