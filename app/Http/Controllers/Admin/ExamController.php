<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Exam, ExamBoard};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('examBoard')->withCount('subjects')->latest()->paginate(10);
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $examBoards = ExamBoard::where('is_active', true)->get();
        return view('admin.exams.create', compact('examBoards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_board_id' => 'required|exists:exam_boards,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        Exam::create($validated);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam created successfully!');
    }

    public function edit(Exam $exam)
    {
        $examBoards = ExamBoard::where('is_active', true)->get();
        return view('admin.exams.edit', compact('exam', 'examBoards'));
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'exam_board_id' => 'required|exists:exam_boards,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $exam->update($validated);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam updated successfully!');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam deleted successfully!');
    }
}
