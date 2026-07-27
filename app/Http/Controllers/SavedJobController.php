<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    public function store(Request $request, Job $job)
    {
        SavedJob::firstOrCreate([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'saved' => true,
                'message' => 'Job saved successfully!',
            ]);
        }

        return back()->with('success', 'Job saved successfully!');
    }

    public function destroy(Request $request, Job $job)
    {
        SavedJob::where('user_id', Auth::id())
            ->where('job_id', $job->id)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'saved' => false,
                'message' => 'Job removed from saved!',
            ]);
        }

        return back()->with('success', 'Job removed from saved!');
    }
}
