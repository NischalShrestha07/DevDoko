<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function create(Job $job)
    {
        if ($job->user_id === Auth::id()) {
            return redirect()->route('jobs.show', $job)->with('error', 'You cannot apply to your own job listing.');
        }

        if ($job->hasApplied(Auth::user())) {
            return redirect()->route('jobs.show', $job)->with('error', 'You have already applied to this job.');
        }

        return view('jobs.apply', compact('job'));
    }

    public function store(Request $request, Job $job)
    {
        if ($job->user_id === Auth::id()) {
            return redirect()->route('jobs.show', $job)->with('error', 'You cannot apply to your own job listing.');
        }

        if ($job->hasApplied(Auth::user())) {
            return redirect()->route('jobs.show', $job)->with('error', 'You have already applied to this job.');
        }

        $data = $request->validate([
            'cover_letter' => 'required|string|min:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $resumePath = $request->file('resume')->store('resumes', 'public');

        $application = JobApplication::create([
            'job_id' => $job->id,
            'user_id' => Auth::id(),
            'cover_letter' => $data['cover_letter'],
            'resume_path' => $resumePath,
            'status' => 'pending',
        ]);

        $job->increment('applications_count');

        Notification::create([
            'user_id' => $job->user_id,
            'from_user_id' => Auth::id(),
            'type' => 'job_application',
            'message' => Auth::user()->name.' applied to your job listing: '.$job->title,
            'data' => [
                'job_id' => $job->id,
                'application_id' => $application->id,
            ],
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Application submitted successfully!');
    }

    public function index()
    {
        $applications = JobApplication::where('user_id', Auth::id())
            ->with('job')
            ->latest()
            ->paginate(10);

        return view('jobs.my-applications', compact('applications'));
    }

    public function applicants(Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            abort(403, 'You can only view applicants for your own job listings.');
        }

        $applications = $job->applications()
            ->with('user.profile')
            ->latest()
            ->paginate(15);

        return view('jobs.applicants', compact('job', 'applications'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        if ($application->job->user_id !== Auth::id()) {
            abort(403, 'You can only manage applicants for your own job listings.');
        }

        $data = $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ]);

        $application->update($data);

        Notification::create([
            'user_id' => $application->user_id,
            'from_user_id' => Auth::id(),
            'type' => 'job_application_status',
            'message' => 'Your application for '.$application->job->title.' was '.$data['status'].'.',
            'data' => [
                'job_id' => $application->job_id,
                'application_id' => $application->id,
                'status' => $data['status'],
            ],
        ]);

        return back()->with('success', 'Applicant status updated.');
    }
}
