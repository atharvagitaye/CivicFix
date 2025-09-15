<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:created,under_process,resolved',
        ]);

        $issue = \App\Models\Issue::findOrFail($id);
        $issue->status = $request->status;
        $issue->save();

        // Notify user about status change (email)
        $user = $issue->user;
        if ($user && $user->email) {
            \Mail::to($user->email)->send(new \App\Mail\IssueStatusUpdated($user, $issue));
        }

        return response()->json([
            'message' => 'Issue status updated',
            'issue' => $issue,
        ]);
    }
    public function index(Request $request)
    {
        $query = \App\Models\Issue::with(['media', 'user']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('category_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }
        if ($request->has('latitude') && $request->has('longitude')) {
            $query->where('latitude', $request->latitude)
                  ->where('longitude', $request->longitude);
        }

        $issues = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($issues);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'latitude' => 'required|string|max:50',
            'longitude' => 'required|string|max:50',
            'user_id' => 'required|exists:users,id',
            'media' => 'array',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240', // 10MB max per file
        ]);

        $issue = new \App\Models\Issue();
        $issue->description = $validated['description'];
        $issue->latitude = $validated['latitude'];
        $issue->longitude = $validated['longitude'];
        $issue->user_id = $validated['user_id'];
        $issue->status = 'created';
        $issue->save();

        // Handle media uploads if present
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('issue_media', 'public');
                $issue->media()->create(['path' => $path]);
            }
        }

        return response()->json([
            'message' => 'Issue reported successfully',
            'issue' => $issue->load('media'),
        ], 201);
    }
}
