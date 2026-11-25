<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function index()
    {
        // Only show parent tests (test series) or tests without parent
        $tests = Test::with(['course', 'subject', 'parent'])
            ->withCount(['questions', 'childTests'])
            ->whereNull('parent_id') // Only parent/standalone tests
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.tests.index', compact('tests'));
    }

    public function create()
    {
        $courses = Course::where('is_published', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $parentTests = Test::whereNull('parent_id')
            ->where('is_test_series', true)
            ->get();

        return view('admin.tests.create', compact('courses', 'subjects', 'parentTests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:tests,id',
            'title' => 'required|string|max:255',
            'test_number' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'duration' => 'nullable|integer|min:0',
            'total_marks' => 'nullable|integer|min:0',
            'passing_marks' => 'nullable|integer|min:0',
            'negative_marking' => 'nullable|numeric|min:0',
            'instructions' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'is_active' => 'boolean',
            'is_test_series' => 'boolean',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $test = Test::create($validated);

        // If it's a test series, attach multiple subjects
        if ($request->is_test_series && $request->has('subjects')) {
            $test->subjects()->attach($request->subjects);
        }

        return redirect()->route('admin.tests.index')->with('success', 'Test created successfully!');
    }

    public function edit(Test $test)
    {
        $courses = Course::where('is_published', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $parentTests = Test::whereNull('parent_id')
            ->where('is_test_series', true)
            ->where('id', '!=', $test->id)
            ->get();
        $test->load('subjects'); // Load test series subjects

        return view('admin.tests.edit', compact('test', 'courses', 'subjects', 'parentTests'));
    }

    public function update(Request $request, Test $test)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:tests,id',
            'title' => 'required|string|max:255',
            'test_number' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'duration' => 'nullable|integer|min:0',
            'total_marks' => 'nullable|integer|min:0',
            'passing_marks' => 'nullable|integer|min:0',
            'negative_marking' => 'nullable|numeric|min:0',
            'instructions' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'is_active' => 'boolean',
            'is_test_series' => 'boolean',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $test->update($validated);

        // Sync subjects for test series
        if ($request->is_test_series && $request->has('subjects')) {
            $test->subjects()->sync($request->subjects);
        } else {
            $test->subjects()->detach();
        }

        return redirect()->route('admin.tests.index')->with('success', 'Test updated successfully!');
    }

    public function destroy(Test $test)
    {
        $test->delete();

        return redirect()->route('admin.tests.index')->with('success', 'Test deleted successfully!');
    }

    // View child tests for a test series
    public function childTests(Test $test, Request $request)
    {
        $query = $test->childTests()
            ->with(['subject'])
            ->withCount('questions');

        // Filter by subject if provided
        if ($request->has('subject')) {
            $query->where('subject_id', $request->subject);
        }

        $childTests = $query->get(); // Get all for grouping
        $test->load('subjects'); // Load subjects for summary cards

        return view('admin.tests.child-tests', compact('test', 'childTests'));
    }
}
