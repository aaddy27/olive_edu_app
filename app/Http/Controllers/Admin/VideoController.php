<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with(['course', 'subject'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        $courses = Course::where('is_published', true)->get();
        $subjects = Subject::where('is_active', true)->get();

        return view('admin.videos.create', compact('courses', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'video_url' => 'required|url',
            'thumbnail' => 'nullable|image|max:5120',
            'duration' => 'nullable|integer|min:0',
            'view_limit' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Prepare data for Video model
        $videoData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course_id' => $validated['course_id'],
            'subject_id' => $validated['subject_id'],
            'video_url' => $validated['video_url'],
            'duration_seconds' => $validated['duration'] ?? 0,
            'view_limit' => $validated['view_limit'] ?? 0,
            'sort_order' => $validated['order'] ?? 0,
            'is_preview' => $validated['is_free'] ?? false,
            'is_free' => $request->has('is_free') ? true : false,
            'is_active' => $request->has('is_active') ? true : false,
            'status' => 'ready',
        ];

        // Extract Vimeo video ID if applicable
        if (strpos($validated['video_url'], 'vimeo.com') !== false) {
            $videoData['video_type'] = 'vimeo';
            preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $validated['video_url'], $matches);
            if (!empty($matches[1])) {
                $videoData['vimeo_video_id'] = $matches[1];
            }
        } elseif (strpos($validated['video_url'], 'youtube.com') !== false || strpos($validated['video_url'], 'youtu.be') !== false) {
            $videoData['video_type'] = 'youtube';
        } else {
            $videoData['video_type'] = 'url';
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $videoData['thumbnail'] = $thumbnailPath;
        }

        Video::create($videoData);

        return redirect()->route('admin.videos.index')->with('success', 'Video created successfully!');
    }

    public function edit(Video $video)
    {
        $courses = Course::where('is_published', true)->get();
        $subjects = Subject::where('is_active', true)->get();

        return view('admin.videos.edit', compact('video', 'courses', 'subjects'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'video_url' => 'required|url',
            'thumbnail' => 'nullable|image|max:5120',
            'duration' => 'nullable|integer|min:0',
            'view_limit' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_free' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Prepare data for Video model
        $videoData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course_id' => $validated['course_id'],
            'subject_id' => $validated['subject_id'],
            'video_url' => $validated['video_url'],
            'duration_seconds' => $validated['duration'] ?? 0,
            'view_limit' => $validated['view_limit'] ?? 0,
            'sort_order' => $validated['order'] ?? 0,
            'is_preview' => $validated['is_free'] ?? false,
            'is_free' => $request->has('is_free') ? true : false,
            'is_active' => $request->has('is_active') ? true : false,
            'status' => 'ready',
        ];

        // Extract Vimeo video ID or YouTube if applicable
        if (strpos($validated['video_url'], 'vimeo.com') !== false) {
            $videoData['video_type'] = 'vimeo';
            preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $validated['video_url'], $matches);
            if (!empty($matches[1])) {
                $videoData['vimeo_video_id'] = $matches[1];
            }
        } elseif (strpos($validated['video_url'], 'youtube.com') !== false || strpos($validated['video_url'], 'youtu.be') !== false) {
            $videoData['video_type'] = 'youtube';
        } else {
            $videoData['video_type'] = 'url';
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $videoData['thumbnail'] = $thumbnailPath;
        }

        $video->update($videoData);

        return redirect()->route('admin.videos.index')->with('success', 'Video updated successfully!');
    }

    public function destroy(Video $video)
    {
        // Delete associated files
        if ($video->video_type === 'upload' && $video->video_url) {
            Storage::disk('public')->delete($video->video_url);
        }
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully!');
    }
}
