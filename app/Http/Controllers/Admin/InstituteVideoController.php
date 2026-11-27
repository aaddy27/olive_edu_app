<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstituteVideoController extends Controller
{
    public function index()
    {
        $videos = Video::forTeachers()
            ->with(['course', 'subject', 'playlist'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.institutes.videos.index', compact('videos'));
    }

    public function create()
    {
        // Strictly Teacher content only!
        $courses = Course::forTeachers()->where('is_published', true)->get();
        $subjects = Subject::forTeachers()->where('is_active', true)->get();
        $playlists = Playlist::forTeachers()->where('is_active', true)->get();

        return view('admin.institutes.videos.create', compact('courses', 'subjects', 'playlists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'playlist_id' => 'nullable|exists:playlists,id',
            'video_url' => 'required|url',
            'thumbnail' => 'nullable|image|max:5120',
            'duration' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $videoData = [
            'target_audience' => 'teacher',
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course_id' => $validated['course_id'],
            'subject_id' => $validated['subject_id'],
            'playlist_id' => $validated['playlist_id'] ?? null,
            'video_url' => $validated['video_url'],
            'duration_seconds' => $validated['duration'] ?? 0,
            'sort_order' => $validated['order'] ?? 0,
            'is_preview' => false,
            'is_free' => true, // Always free for teachers
            'is_active' => $request->has('is_active'),
            'status' => 'ready',
        ];

        // Vimeo / YouTube identification
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

        if ($request->hasFile('thumbnail')) {
            $videoData['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Video::create($videoData);

        return redirect()->route('admin.institute-videos.index')
            ->with('success', 'Teacher Video uploaded successfully!');
    }

    public function edit(Video $instituteVideo)
    {
        $courses = Course::forTeachers()->where('is_published', true)->get();
        $subjects = Subject::forTeachers()->where('is_active', true)->get();
        $playlists = Playlist::forTeachers()->where('is_active', true)->get();

        return view('admin.institutes.videos.edit', [
            'video' => $instituteVideo,
            'courses' => $courses,
            'subjects' => $subjects,
            'playlists' => $playlists,
        ]);
    }

    public function update(Request $request, Video $instituteVideo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'playlist_id' => 'nullable|exists:playlists,id',
            'video_url' => 'required|url',
            'thumbnail' => 'nullable|image|max:5120',
            'duration' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $videoData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course_id' => $validated['course_id'],
            'subject_id' => $validated['subject_id'],
            'playlist_id' => $validated['playlist_id'] ?? null,
            'video_url' => $validated['video_url'],
            'duration_seconds' => $validated['duration'] ?? 0,
            'sort_order' => $validated['order'] ?? 0,
            'is_free' => true,
            'is_active' => $request->has('is_active'),
        ];

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

        if ($request->hasFile('thumbnail')) {
            $videoData['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $instituteVideo->update($videoData);

        return redirect()->route('admin.institute-videos.index')
            ->with('success', 'Teacher Video updated successfully!');
    }

    public function destroy(Video $instituteVideo)
    {
        $instituteVideo->delete();

        return redirect()->route('admin.institute-videos.index')
            ->with('success', 'Teacher Video deleted successfully!');
    }
}
