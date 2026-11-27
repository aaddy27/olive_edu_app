<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Course, Subject, ExamBoard};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InstituteCourseController extends Controller
{
    public function index()
    {
        $courses = Course::forTeachers()
            ->with(['examBoard', 'subjects'])
            ->withCount(['videos', 'playlists'])
            ->latest()
            ->paginate(10);

        return view('admin.institutes.courses.index', compact('courses'));
    }

    public function create()
    {
        $examBoards = ExamBoard::where('is_active', true)->get();
        // Only load teacher subjects!
        $subjects = Subject::forTeachers()->where('is_active', true)->get();

        return view('admin.institutes.courses.create', compact('examBoards', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_board_id' => 'nullable|exists:exam_boards,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'thumbnail_path' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $validated['thumbnail_path'] = $path;
        }

        $validated['target_audience'] = 'teacher';
        $validated['price'] = 0; // 100% Free for institute teachers
        $validated['currency'] = 'INR';
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');
        $validated['created_by'] = Auth::id();

        $course = Course::create($validated);

        // Attach teacher subjects with sort order
        $subjects = [];
        foreach ($request->subjects as $index => $subjectId) {
            $subjects[$subjectId] = ['sort_order' => $index];
        }
        $course->subjects()->attach($subjects);

        return redirect()->route('admin.institute-courses.index')
            ->with('success', 'Teacher Course created successfully!');
    }

    public function edit(Course $instituteCourse)
    {
        $examBoards = ExamBoard::where('is_active', true)->get();
        $subjects = Subject::forTeachers()->where('is_active', true)->get();
        $selectedSubjects = $instituteCourse->subjects->pluck('id')->toArray();

        return view('admin.institutes.courses.edit', [
            'course' => $instituteCourse,
            'examBoards' => $examBoards,
            'subjects' => $subjects,
            'selectedSubjects' => $selectedSubjects,
        ]);
    }

    public function update(Request $request, Course $instituteCourse)
    {
        $validated = $request->validate([
            'exam_board_id' => 'nullable|exists:exam_boards,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'thumbnail_path' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($instituteCourse->thumbnail_path && !filter_var($instituteCourse->thumbnail_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($instituteCourse->thumbnail_path);
            }
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');

        $instituteCourse->update($validated);

        $subjects = [];
        foreach ($request->subjects as $index => $subjectId) {
            $subjects[$subjectId] = ['sort_order' => $index];
        }
        $instituteCourse->subjects()->sync($subjects);

        return redirect()->route('admin.institute-courses.index')
            ->with('success', 'Teacher Course updated successfully!');
    }

    public function destroy(Course $instituteCourse)
    {
        $instituteCourse->delete();

        return redirect()->route('admin.institute-courses.index')
            ->with('success', 'Teacher Course deleted successfully!');
    }
}
