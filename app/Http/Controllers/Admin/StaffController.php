<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staffMembers = Staff::with(['user'])
            ->withCount('issueAssignments')
            ->latest()
            ->paginate(15);

        return view('admin.staff.index', compact('staffMembers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get users who are not already staff or admin
        $regularUsers = User::whereDoesntHave('staff')
            ->whereDoesntHave('admin')
            ->orderBy('name')
            ->get();

        return view('admin.staff.create', compact('regularUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:staff,user_id',
            'phone' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if user is already admin
        $user = User::find($request->user_id);
        if ($user->admin) {
            return redirect()->back()
                ->with('error', 'Cannot promote admin users to staff.')
                ->withInput();
        }

        Staff::create([
            'user_id' => $request->user_id,
            'phone' => $request->phone,
            'department' => $request->department,
            'employee_id' => $request->employee_id,
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'User promoted to staff successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        $staff->load('user');
        $staff->loadCount('issueAssignments');
        
        // Generate statistics for assigned issues
        $stats = [
            'total_assigned' => $staff->issueAssignments()->count(),
            'in_progress' => $staff->assignedIssues()->where('status', 'in_progress')->count(),
            'resolved' => $staff->assignedIssues()->where('status', 'resolved')->count(),
            'pending' => $staff->assignedIssues()->where('status', 'pending')->count(),
        ];
        
        $recentAssignments = $staff->issueAssignments()
            ->with(['issue.category', 'issue.subcategory', 'issue.user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.staff.show', compact('staff', 'recentAssignments', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        $staff->load('user');
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $staff->load('user');
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request, $staff) {
            // Update user information
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $staff->user->update($updateData);

            // Update staff information
            $staff->update([
                'phone' => $request->phone,
                'department' => $request->department,
                'employee_id' => $request->employee_id,
            ]);
        });

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $staff->load('user');
        
        // Check if staff has assigned issues
        $assignedIssuesCount = $staff->issueAssignments()->count();
        
        if ($assignedIssuesCount > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete staff member. They have {$assignedIssuesCount} assigned issues.");
        }

        DB::transaction(function () use ($staff) {
            $user = $staff->user;
            $staff->delete();
            $user->delete();
        });

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully!');
    }
}
