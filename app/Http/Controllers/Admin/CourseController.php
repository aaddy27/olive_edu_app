<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Course, Subject, ExamBoard};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('examBoard', 'subjects')->latest()->paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $examBoards = ExamBoard::where('is_active', true)->get();
        $subjects = Subject::with('exam')->where('is_active', true)->get();
        return view('admin.courses.create', compact('examBoards', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_board_id' => 'required|exists:exam_boards,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'thumbnail_path' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $validated['thumbnail_path'] = $path;
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');
        $validated['created_by'] = Auth::id();

        $course = Course::create($validated);
        
        // Attach subjects with sort order
        $subjects = [];
        foreach ($request->subjects as $index => $subjectId) {
            $subjects[$subjectId] = ['sort_order' => $index];
        }
        $course->subjects()->attach($subjects);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully with subjects!');
    }

    public function edit(Course $course)
    {
        $examBoards = ExamBoard::where('is_active', true)->get();
        $subjects = Subject::with('exam')->where('is_active', true)->get();
        $selectedSubjects = $course->subjects->pluck('id')->toArray();
        return view('admin.courses.edit', compact('course', 'examBoards', 'subjects', 'selectedSubjects'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'exam_board_id' => 'required|exists:exam_boards,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'thumbnail_path' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old file if stored locally
            if ($course->thumbnail_path && !filter_var($course->thumbnail_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($course->thumbnail_path);
            }
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $validated['thumbnail_path'] = $path;
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');

        $course->update($validated);
        
        // Sync subjects with sort order
        $subjects = [];
        foreach ($request->subjects as $index => $subjectId) {
            $subjects[$subjectId] = ['sort_order' => $index];
        }
        $course->subjects()->sync($subjects);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully!');
    }
}
