<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InstitutePlaylistController extends Controller
{
    public function index(Request $request)
    {
        $query = Playlist::forTeachers()->with(['course', 'subject'])->withCount('videos');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $playlists = $query->orderBy('sort_order')->latest()->paginate(15)->withQueryString();
        // Only teacher courses!
        $courses = Course::forTeachers()->where('is_published', true)->get();

        return view('admin.institutes.playlists.index', compact('playlists', 'courses'));
    }

    public function create()
    {
        $courses = Course::forTeachers()->with('subjects')->where('is_published', true)->get();
        $subjects = Subject::forTeachers()->where('is_active', true)->get();

        return view('admin.institutes.playlists.create', compact('courses', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'title' => 'required|string|max:190',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('playlists/thumbnails', 'public');
        }

        Playlist::create([
            'target_audience' => 'teacher',
            'course_id' => $validated['course_id'],
            'subject_id' => $validated['subject_id'] ?? null,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-tchr-' . Str::lower(Str::random(4)),
            'description' => $validated['description'] ?? null,
            'thumbnail_path' => $thumbnailPath,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.institute-playlists.index')
            ->with('success', 'Teacher Playlist created successfully!');
    }

    public function edit(Playlist $institutePlaylist)
    {
        $courses = Course::forTeachers()->with('subjects')->where('is_published', true)->get();
        $subjects = Subject::forTeachers()->where('is_active', true)->get();

        return view('admin.institutes.playlists.edit', [
            'playlist' => $institutePlaylist,
            'courses' => $courses,
            'subjects' => $subjects,
        ]);
    }

    public function update(Request $request, Playlist $institutePlaylist)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'title' => 'required|string|max:190',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($institutePlaylist->thumbnail_path) {
                Storage::disk('public')->delete($institutePlaylist->thumbnail_path);
            }
            $institutePlaylist->thumbnail_path = $request->file('thumbnail')->store('playlists/thumbnails', 'public');
        }

        $institutePlaylist->update([
            'course_id' => $validated['course_id'],
            'subject_id' => $validated['subject_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.institute-playlists.index')
            ->with('success', 'Teacher Playlist updated successfully!');
    }

    public function destroy(Playlist $institutePlaylist)
    {
        if ($institutePlaylist->thumbnail_path) {
            Storage::disk('public')->delete($institutePlaylist->thumbnail_path);
        }

        $institutePlaylist->delete();

        return redirect()->route('admin.institute-playlists.index')
            ->with('success', 'Teacher Playlist deleted successfully!');
    }
}
