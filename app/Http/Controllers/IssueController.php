<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $issues = Issue::with(['user', 'assignment.staff.user', 'category', 'subcategory'])
                ->latest()
                ->paginate(15);
        } elseif ($user->isStaff()) {
            $issues = Issue::with(['user', 'category', 'subcategory', 'assignment'])
                ->assignedTo($user->staff->id)
                ->latest()
                ->paginate(15);
        } else {
            $issues = Issue::with(['assignment.staff.user', 'category', 'subcategory'])
                ->reportedBy($user->id)
                ->latest()
                ->paginate(15);
        }

        return view('issues.index', compact('issues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with('subCategories')->get();
        $priorities = Issue::getPriorityLevels();
        
        return view('issues.create', compact('categories', 'priorities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Issue creation started', ['user_id' => Auth::id(), 'request_data' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'location_lat' => 'required|numeric|between:-90,90',
            'location_lng' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            \Log::warning('Issue creation validation failed', ['errors' => $validator->errors()->toArray()]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $issue = Issue::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'status' => 'submitted',
                'priority' => $request->priority,
                'location' => 'Location from coordinates', // Placeholder text since location field is required
                'latitude' => $request->location_lat,
                'longitude' => $request->location_lng,
            ]);

            \Log::info('Issue created successfully', ['issue_id' => $issue->id]);

            return redirect()->route('issues.show', $issue)
                ->with('success', 'Issue reported successfully!');
        } catch (\Exception $e) {
            \Log::error('Issue creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->with('error', 'Failed to create issue. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        $issue->load(['user', 'category', 'subcategory', 'assignment.staff.user', 'updates.staff.user', 'updates.status']);
        
        // Provide status options for the update form
        $statusOptions = [
            'submitted' => 'Open',
            'in_progress' => 'In Progress', 
            'resolved' => 'Resolved'
        ];
        
        return view('issues.show', compact('issue', 'statusOptions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        // Only allow reporter or admin to edit
        if (!Auth::user()->isAdmin() && $issue->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to edit this issue.');
        }

        $categories = Category::with('subCategories')->get();
        
        return view('issues.edit', compact('issue', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Issue $issue)
    {
        // Only allow reporter or admin to update
        if (!Auth::user()->isAdmin() && $issue->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to update this issue.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $issue->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'priority' => $request->priority,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Issue updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        // Only admin can delete issues
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized to delete this issue.');
        }

        $issue->delete();

        return redirect()->route('issues.index')
            ->with('success', 'Issue deleted successfully!');
    }
}
