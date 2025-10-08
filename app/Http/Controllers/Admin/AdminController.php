<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Staff;
use App\Models\IssueAssignment;
use App\Models\User;
use App\Models\Admin;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_issues' => Issue::count(),
            'unassigned_issues' => Issue::whereDoesntHave('assignment')->count(),
            'total_staff' => Staff::count(),
            'total_users' => User::count(),
        ];

        $unassignedIssues = Issue::with(['user', 'category'])
            ->whereDoesntHave('assignment')
            ->latest()
            ->take(10)
            ->get();

        // Get issues by category for chart
        $issuesByCategory = Category::withCount('issues')->get();

        // Get recent activity (last 10 issues)
        $recentActivity = Issue::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        // Get status distribution
        $statusDistribution = Issue::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Get priority distribution
        $priorityDistribution = Issue::selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats', 
            'unassignedIssues', 
            'issuesByCategory', 
            'recentActivity',
            'statusDistribution',
            'priorityDistribution'
        ));
    }

    /**
     * Show form to assign issue to staff.
     */
    public function assignIssue(Issue $issue)
    {
        $staffMembers = Staff::with('user')->get();
        return view('admin.issues.assign', compact('issue', 'staffMembers'));
    }

    /**
     * Assign issue to staff member.
     */
    public function storeAssignment(Request $request, Issue $issue)
    {
        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:staff,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create assignment record (no need to update issue table)
        IssueAssignment::create([
            'issue_id' => $issue->id,
            'staff_id' => $request->staff_id,
            'assigned_by' => Auth::user()->admin->id,
            'assigned_at' => now(),
        ]);

        return redirect()->route('admin.issues.index')
            ->with('success', 'Issue assigned successfully!');
    }

    /**
     * Promote a user to admin.
     */
    public function promoteToAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail($request->user_id);

        // Check if user is already an admin
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'User is already an admin.');
        }

        // Create admin record
        Admin::create(['user_id' => $user->id]);

        return redirect()->back()->with('success', 'User promoted to admin successfully!');
    }

    /**
     * Promote a user to staff.
     */
    public function promoteToStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail($request->user_id);

        // Check if user is already staff
        if ($user->isStaff()) {
            return redirect()->back()->with('error', 'User is already staff.');
        }

        // Create staff record
        Staff::create(['user_id' => $user->id]);

        return redirect()->back()->with('success', 'User promoted to staff successfully!');
    }

    /**
     * Show all users for management.
     */
    public function users()
    {
        $users = User::with(['admin', 'staff'])
            ->latest()
            ->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Show all issues for admin management.
     */
    public function issues(Request $request)
    {
        $query = Issue::with(['user', 'category', 'subcategory', 'assignment.staff.user']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $issues = $query->latest()->paginate(15);
        
        $categories = Category::all();
        $staffMembers = Staff::with('user')->get();

        return view('admin.issues.index', compact('issues', 'categories', 'staffMembers'));
    }
}
