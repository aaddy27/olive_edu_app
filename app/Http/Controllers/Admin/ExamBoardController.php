<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExamBoardController extends Controller
{
    public function index()
    {
        $examBoards = ExamBoard::withCount('exams')->latest()->paginate(10);
        return view('admin.exam-boards.index', compact('examBoards'));
    }

    public function create()
    {
        return view('admin.exam-boards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        ExamBoard::create($validated);

        return redirect()->route('admin.exam-boards.index')
            ->with('success', 'Exam Board created successfully!');
    }

    public function edit(ExamBoard $examBoard)
    {
        return view('admin.exam-boards.edit', compact('examBoard'));
    }

    public function update(Request $request, ExamBoard $examBoard)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $examBoard->update($validated);

        return redirect()->route('admin.exam-boards.index')
            ->with('success', 'Exam Board updated successfully!');
    }

    public function destroy(ExamBoard $examBoard)
    {
        $examBoard->delete();
        return redirect()->route('admin.exam-boards.index')
            ->with('success', 'Exam Board deleted successfully!');
    }
}
