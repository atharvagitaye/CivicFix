<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\IssueUpdate;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    /**
     * Show the staff dashboard.
     */
    public function dashboard()
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
     * Show all assigned issues.
     */
    public function assignedIssues(Request $request)
    {
        $staff = Auth::user()->staff;
        
        $query = Issue::with(['user', 'category', 'subcategory', 'assignment'])
            ->whereHas('assignment', function($q) use ($staff) {
                $q->where('staff_id', $staff->id);
            });

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $issues = $query->latest()->get();
        
        // Filter issues for the view
        $filteredIssues = $issues;
        if ($request->hasAny(['status', 'priority', 'search'])) {
            $filteredIssues = $query->latest()->get();
        }

        return view('staff.issues.index', compact('issues', 'filteredIssues'));
    }

    /**
     * Show specific issue details.
     */
    public function showIssue(Issue $issue)
    {
        // Ensure staff can only view their assigned issues
        $staff = Auth::user()->staff;
        if (!$issue->assignment || $issue->assignment->staff_id !== $staff->id) {
            abort(403, 'Unauthorized to view this issue.');
        }

        $issue->load(['user', 'category', 'subcategory', 'assignment', 'updates.staff.user', 'media']);

        // Provide status options for the update form
        $statusOptions = [
            'submitted' => 'Open',
            'in_progress' => 'In Progress', 
            'resolved' => 'Resolved'
        ];

        return view('issues.show', compact('issue', 'statusOptions'));
    }

    /**
     * Update issue status with description.
     */
    public function updateIssue(Request $request, Issue $issue)
    {
        // Ensure staff can only update their assigned issues
        $staff = Auth::user()->staff;
        if (!$issue->assignment || $issue->assignment->staff_id !== $staff->id) {
            abort(403, 'Unauthorized to update this issue.');
        }

        // Accept either 'update_description' (old view) or 'notes' (modal)
        $input = $request->all();
        if (isset($input['notes']) && !isset($input['update_description'])) {
            $input['update_description'] = $input['notes'];
        }

        $validator = Validator::make($input, [
            'status' => 'required|in:submitted,in_progress,resolved',
            'update_description' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the issue status
        $issue->update(['status' => $input['status']]);

        // Map string status to status_id for issue_updates table
        $statusMapping = [
            'submitted' => 1,  // Open
            'in_progress' => 2, // In Progress
            'resolved' => 3,    // Resolved
        ];

        // Create an issue update record
        $update = IssueUpdate::create([
            'issue_id' => $issue->id,
            'updated_by' => Auth::user()->staff->id,
            'status_id' => $statusMapping[$input['status']] ?? 1,
            'update_description' => $input['update_description'],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Issue updated', 'update_id' => $update->id]);
        }

        return redirect()->back()->with('success', 'Issue updated successfully!');
    }

    /**
     * Mark issue as completed.
     */
    public function completeIssue(Issue $issue)
    {
        // Ensure staff can only complete their assigned issues
        $staff = Auth::user()->staff;
        if (!$issue->assignment || $issue->assignment->staff_id !== $staff->id) {
            abort(403, 'Unauthorized to complete this issue.');
        }

        // Update the issue status to resolved
        $issue->update(['status' => 'resolved']);

        // Map to status_id and create an issue update record
        $statusMapping = [
            'submitted' => 1,
            'in_progress' => 2,
            'resolved' => 3,
        ];

        IssueUpdate::create([
            'issue_id' => $issue->id,
            'updated_by' => $staff->id,
            'status_id' => $statusMapping['resolved'],
            'update_description' => 'Issue marked as resolved by staff member.',
        ]);

        return redirect()->route('staff.dashboard')
            ->with('success', 'Issue completed successfully!');
    }
}
