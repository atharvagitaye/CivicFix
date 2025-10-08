<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssueMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    /**
     * Store media files for an issue.
     */
    public function store(Request $request, Issue $issue)
    {
        $validator = Validator::make($request->all(), [
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:5120', // 5MB max
            'media_type' => 'required|in:photo,document',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('media');
            
            // Generate unique filename
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Store file in public storage
            $path = $file->storeAs('issues/media', $filename, 'public');
            
            // Create media record
            IssueMedia::create([
                'issue_id' => $issue->id,
                'media_type' => $request->media_type,
                'media_url' => Storage::url($path),
            ]);

            return redirect()->back()
                ->with('success', 'Media uploaded successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to upload media. Please try again.');
        }
    }

    /**
     * Delete a media file.
     */
    public function destroy(IssueMedia $media)
    {
        try {
            // Delete file from storage
            $relativePath = str_replace('/storage/', '', $media->media_url);
            Storage::disk('public')->delete($relativePath);

            // Delete database record
            $media->delete();

            return redirect()->back()
                ->with('success', 'Media deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete media. Please try again.');
        }
    }

    /**
     * Display media file.
     */
    public function show(IssueMedia $media)
    {
        $relativePath = str_replace('/storage/', '', $media->media_url);
        
        if (!Storage::disk('public')->exists($relativePath)) {
            abort(404, 'Media file not found.');
        }

        return Storage::disk('public')->response($relativePath);
    }
}
