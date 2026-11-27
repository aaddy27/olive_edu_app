<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Subject, Exam};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstituteSubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::forTeachers()
            ->with('courses')
            ->withCount('videos')
            ->latest()
            ->paginate(10);

        return view('admin.institutes.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $exams = Exam::where('is_active', true)->get();
        return view('admin.institutes.subjects.create', compact('exams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'nullable|exists:exams,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['target_audience'] = 'teacher';
        $validated['slug'] = Str::slug($validated['name']) . '-tchr-' . Str::lower(Str::random(4));
        $validated['is_active'] = $request->has('is_active');

        Subject::create($validated);

        return redirect()->route('admin.institute-subjects.index')
            ->with('success', 'Teacher Subject created successfully!');
    }

    public function edit(Subject $instituteSubject)
    {
        $exams = Exam::where('is_active', true)->get();
        return view('admin.institutes.subjects.edit', [
            'subject' => $instituteSubject,
            'exams' => $exams,
        ]);
    }

    public function update(Request $request, Subject $instituteSubject)
    {
        $validated = $request->validate([
            'exam_id' => 'nullable|exists:exams,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $instituteSubject->update($validated);

        return redirect()->route('admin.institute-subjects.index')
            ->with('success', 'Teacher Subject updated successfully!');
    }

    public function destroy(Subject $instituteSubject)
    {
        $instituteSubject->delete();

        return redirect()->route('admin.institute-subjects.index')
            ->with('success', 'Teacher Subject deleted successfully!');
    }
}
