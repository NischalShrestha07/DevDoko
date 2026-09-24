<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function toggleVerified(User $user)
    {
        $user->profile()->update(['is_verified' => ! $user->profile->is_verified]);

        return back()->with('success', $user->profile->is_verified ? 'User verified.' : 'Verification removed.');
    }

    public function reports()
    {
        $reports = Report::with(['reporter.profile', 'reportable'])
            ->orderByRaw("status = 'pending' desc")
            ->latest()
            ->paginate(20);

        return view('admin.reports', compact('reports'));
    }

    public function resolveReport(Report $report)
    {
        $report->reportable?->delete();
        $report->update(['status' => 'resolved']);

        return back()->with('success', 'Report resolved — content removed.');
    }

    public function dismissReport(Report $report)
    {
        $report->update(['status' => 'dismissed']);

        return back()->with('success', 'Report dismissed.');
    }
}
