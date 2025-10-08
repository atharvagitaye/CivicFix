<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isStaff()) {
            return $this->staffDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    /**
     * Admin dashboard with overall statistics.
     */
    private function adminDashboard()
    {
        $stats = [
            'total_issues' => Issue::count(),
            'unassigned_issues' => Issue::whereDoesntHave('assignment')->count(),
            'open_issues' => Issue::where('status', 'submitted')->count(),
            'in_progress_issues' => Issue::where('status', 'in_progress')->count(),
            'closed_issues' => Issue::where('status', 'closed')->count(),
            'high_priority_issues' => Issue::where('priority', 'high')->count(),
        ];

        $unassignedIssues = Issue::with(['user', 'category'])
            ->whereDoesntHave('assignment')
            ->latest()
            ->take(10)
            ->get();

        $recentIssues = Issue::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        $issuesByCategory = Category::withCount('issues')->get();

        return view('admin.dashboard', compact('stats', 'unassignedIssues', 'recentIssues', 'issuesByCategory'));
    }

        /**
     * Staff dashboard view with assignments.
     */
    private function staffDashboard()
    {
        $staff = Auth::user()->staff;
        
        $stats = [
            'assigned_issues' => Issue::whereHas('assignment', function($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })->count(),
            'pending_issues' => Issue::whereHas('assignment', function($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })->whereIn('status', ['submitted', 'in_progress'])->count(),
            'completed_issues' => Issue::whereHas('assignment', function($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })->where('status', 'resolved')->count(),
        ];

        $assignedIssues = Issue::with(['user', 'category', 'assignment'])
            ->whereHas('assignment', function($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            })
            ->latest()
            ->take(10)
            ->get();

        return view('staff.dashboard', compact('stats', 'assignedIssues'));
    }

    /**
     * User dashboard with reported issues.
     */
    private function userDashboard()
    {
        $stats = [
            'total_reported' => Issue::where('user_id', Auth::id())->count(),
            'open_issues' => Issue::where('user_id', Auth::id())
                ->where('status', 'submitted')->count(),
            'in_progress_issues' => Issue::where('user_id', Auth::id())
                ->where('status', 'in_progress')->count(),
            'resolved_issues' => Issue::where('user_id', Auth::id())
                ->where('status', 'resolved')->count(),
        ];

        $recentIssues = Issue::with(['category', 'assignment.staff.user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('stats', 'recentIssues'));
    }
}
