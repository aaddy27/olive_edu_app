<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        $query = Playlist::forStudents()->with(['course', 'subject'])->withCount('videos');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $playlists = $query->orderBy('sort_order')->latest()->paginate(15)->withQueryString();
        $courses = Course::forStudents()->where('is_published', true)->get();

        return view('admin.playlists.index', compact('playlists', 'courses'));
    }

    public function create()
    {
        $courses = Course::forStudents()->with('subjects')->where('is_published', true)->get();
        $subjects = Subject::forStudents()->where('is_active', true)->get();

        return view('admin.playlists.create', compact('courses', 'subjects'));
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
            'target_audience' => 'student',
            'course_id' => $validated['course_id'],
            'subject_id' => $validated['subject_id'] ?? null,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'thumbnail_path' => $thumbnailPath,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.playlists.index')
            ->with('success', 'Playlist created successfully!');
    }

    public function edit(Playlist $playlist)
    {
        $courses = Course::forStudents()->with('subjects')->where('is_published', true)->get();
        $subjects = Subject::forStudents()->where('is_active', true)->get();

        return view('admin.playlists.edit', compact('playlist', 'courses', 'subjects'));
    }

    public function update(Request $request, Playlist $playlist)
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
            if ($playlist->thumbnail_path) {
                Storage::disk('public')->delete($playlist->thumbnail_path);
            }
            $playlist->thumbnail_path = $request->file('thumbnail')->store('playlists/thumbnails', 'public');
        }

        $playlist->update([
            'course_id' => $validated['course_id'],
            'subject_id' => $validated['subject_id'] ?? null,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.playlists.index')
            ->with('success', 'Playlist updated successfully!');
    }

    public function destroy(Playlist $playlist)
    {
        if ($playlist->thumbnail_path) {
            Storage::disk('public')->delete($playlist->thumbnail_path);
        }

        $playlist->delete();

        return redirect()->route('admin.playlists.index')
            ->with('success', 'Playlist deleted successfully!');
    }
}
