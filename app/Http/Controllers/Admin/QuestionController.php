<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Test $test)
    {
        $questions = $test->questions()
            ->orderBy('id')
            ->paginate(20);

        return view('admin.questions.index', compact('test', 'questions'));
    }

    public function create(Test $test)
    {
        $test->load('subjects'); // Load test series subjects
        return view('admin.questions.create', compact('test'));
    }

    public function store(Request $request, Test $test)
    {
        $validated = $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'nullable|string',
            'option_d' => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d',
            'marks' => 'nullable|numeric|min:0',
            'negative_marks' => 'nullable|numeric|min:0',
            'explanation' => 'nullable|string',
        ]);

        // Handle image upload - Save directly in public folder for shared hosting
        $imagePath = null;
        if ($request->hasFile('question_image')) {
            $file = $request->file('question_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/questions'), $filename);
            $imagePath = 'uploads/questions/' . $filename;
        }

        // Create options array
        $options = [
            'a' => $validated['option_a'],
            'b' => $validated['option_b'],
        ];
        
        if (!empty($validated['option_c'])) {
            $options['c'] = $validated['option_c'];
        }
        if (!empty($validated['option_d'])) {
            $options['d'] = $validated['option_d'];
        }

        Question::create([
            'test_id' => $test->id,
            'subject_id' => $validated['subject_id'] ?? null,
            'question_text' => $validated['question_text'],
            'question_image' => $imagePath,
            'options' => $options,
            'correct_option' => $validated['correct_answer'],
            'marks' => $validated['marks'] ?? 1,
            'negative_marks' => $validated['negative_marks'] ?? 0,
            'explanation' => $validated['explanation'],
        ]);

        return redirect()->route('admin.tests.questions.index', $test)->with('success', 'Question added successfully!');
    }

    public function edit(Test $test, Question $question)
    {
        $test->load('subjects'); // Load test series subjects
        return view('admin.questions.edit', compact('test', 'question'));
    }

    public function update(Request $request, Test $test, Question $question)
    {
        $validated = $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'nullable|string',
            'option_d' => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d',
            'marks' => 'nullable|numeric|min:0',
            'negative_marks' => 'nullable|numeric|min:0',
            'explanation' => 'nullable|string',
        ]);

        // Handle image upload - Save directly in public folder for shared hosting
        if ($request->hasFile('question_image')) {
            // Delete old image if exists
            if ($question->question_image) {
                $oldImagePath = public_path($question->question_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file = $request->file('question_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/questions'), $filename);
            $validated['question_image'] = 'uploads/questions/' . $filename;
        }

        // Create options array
        $options = [
            'a' => $validated['option_a'],
            'b' => $validated['option_b'],
        ];
        
        if (!empty($validated['option_c'])) {
            $options['c'] = $validated['option_c'];
        }
        if (!empty($validated['option_d'])) {
            $options['d'] = $validated['option_d'];
        }

        $question->update([
            'subject_id' => $validated['subject_id'] ?? null,
            'question_text' => $validated['question_text'],
            'question_image' => $validated['question_image'] ?? $question->question_image,
            'options' => $options,
            'correct_option' => $validated['correct_answer'],
            'marks' => $validated['marks'] ?? 1,
            'negative_marks' => $validated['negative_marks'] ?? 0,
            'explanation' => $validated['explanation'],
        ]);

        return redirect()->route('admin.tests.questions.index', $test)->with('success', 'Question updated successfully!');
    }

    public function destroy(Test $test, Question $question)
    {
        // Delete image if exists
        if ($question->question_image) {
            $imagePath = public_path($question->question_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $question->delete();

        return redirect()->route('admin.tests.questions.index', $test)->with('success', 'Question deleted successfully!');
    }

    /**
     * Download a sample Excel template for bulk importing questions.
     */
    public function downloadSample(Test $test)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Questions Template');

        // Headers
        $headers = [
            'A1' => 'Question Text',
            'B1' => 'Option A',
            'C1' => 'Option B',
            'D1' => 'Option C',
            'E1' => 'Option D',
            'F1' => 'Correct Answer',
            'G1' => 'Marks',
            'H1' => 'Negative Marks',
            'I1' => 'Explanation'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Header styling
        $headerRange = 'A1:I1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F46E5');
        $sheet->getRowDimension(1)->setRowHeight(24);

        // Sample rows
        $sampleData = [
            [
                'What is the capital of India?',
                'Mumbai',
                'New Delhi',
                'Kolkata',
                'Bengaluru',
                'B',
                '1',
                '0.25',
                'New Delhi is the official capital of India.'
            ],
            [
                'Which organelle is known as the powerhouse of the cell?',
                'Ribosome',
                'Mitochondria',
                'Nucleus',
                'Endoplasmic Reticulum',
                'B',
                '1',
                '0.25',
                'Mitochondria generate most of the chemical energy needed to power the cell.'
            ],
            [
                'What is the SI unit of electric current?',
                'Volt',
                'Ohm',
                'Ampere',
                'Watt',
                'C',
                '2',
                '0.5',
                'The ampere is the base unit of electric current in the International System of Units.'
            ]
        ];

        $rowNum = 2;
        foreach ($sampleData as $row) {
            $colLetter = 'A';
            foreach ($row as $val) {
                $sheet->setCellValue($colLetter . $rowNum, $val);
                $colLetter++;
            }
            $rowNum++;
        }

        // Auto size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'questions_template_' . \Illuminate\Support\Str::slug($test->title) . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Import questions from uploaded Excel or CSV file.
     */
    public function importExcel(Request $request, Test $test)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ], [
            'excel_file.required' => 'Please select an Excel (.xlsx, .xls) or CSV (.csv) file to upload.',
            'excel_file.mimes' => 'The file must be an Excel (.xlsx, .xls) or CSV (.csv) file.',
            'excel_file.max' => 'The file size must not exceed 10MB.',
        ]);

        $file = $request->file('excel_file');

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);

            if (empty($rows) || count($rows) < 2) {
                return redirect()->back()->with('error', 'The uploaded file is empty or does not contain any questions.');
            }

            // Find header row and map column names to indices
            $headerRow = $rows[0];
            $colMap = [];

            foreach ($headerRow as $index => $colName) {
                if (empty($colName)) continue;
                $cleaned = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', (string)$colName)));
                
                if (in_array($cleaned, ['questiontext', 'question', 'qtext', 'ques'])) {
                    $colMap['question_text'] = $index;
                } elseif (in_array($cleaned, ['optiona', 'a', 'opta', 'choicea'])) {
                    $colMap['option_a'] = $index;
                } elseif (in_array($cleaned, ['optionb', 'b', 'optb', 'choiceb'])) {
                    $colMap['option_b'] = $index;
                } elseif (in_array($cleaned, ['optionc', 'c', 'optc', 'choicec'])) {
                    $colMap['option_c'] = $index;
                } elseif (in_array($cleaned, ['optiond', 'd', 'optd', 'choiced'])) {
                    $colMap['option_d'] = $index;
                } elseif (in_array($cleaned, ['correctanswer', 'answer', 'correctoption', 'correct', 'ans', 'key'])) {
                    $colMap['correct_answer'] = $index;
                } elseif (in_array($cleaned, ['marks', 'mark', 'points'])) {
                    $colMap['marks'] = $index;
                } elseif (in_array($cleaned, ['negativemarks', 'negative', 'negmarks', 'negmark'])) {
                    $colMap['negative_marks'] = $index;
                } elseif (in_array($cleaned, ['explanation', 'solution', 'explain', 'exp'])) {
                    $colMap['explanation'] = $index;
                }
            }

            // Fallback if headers are not matched by names, assume standard template order:
            if (!isset($colMap['question_text'])) {
                $colMap['question_text'] = 0;
                $colMap['option_a'] = 1;
                $colMap['option_b'] = 2;
                $colMap['option_c'] = 3;
                $colMap['option_d'] = 4;
                $colMap['correct_answer'] = 5;
                $colMap['marks'] = 6;
                $colMap['negative_marks'] = 7;
                $colMap['explanation'] = 8;
            }

            $importedCount = 0;
            $skippedCount = 0;

            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                $qText = isset($colMap['question_text']) ? trim((string)($row[$colMap['question_text']] ?? '')) : '';
                if ($qText === '') {
                    $skippedCount++;
                    continue;
                }

                $optA = isset($colMap['option_a']) ? trim((string)($row[$colMap['option_a']] ?? '')) : '';
                $optB = isset($colMap['option_b']) ? trim((string)($row[$colMap['option_b']] ?? '')) : '';
                $optC = isset($colMap['option_c']) ? trim((string)($row[$colMap['option_c']] ?? '')) : '';
                $optD = isset($colMap['option_d']) ? trim((string)($row[$colMap['option_d']] ?? '')) : '';

                // At least Option A and Option B are required
                if ($optA === '' || $optB === '') {
                    $skippedCount++;
                    continue;
                }

                $options = ['a' => $optA, 'b' => $optB];
                if ($optC !== '') $options['c'] = $optC;
                if ($optD !== '') $options['d'] = $optD;

                // Normalize correct option ('A', 'a', '1', etc.)
                $rawAns = isset($colMap['correct_answer']) ? strtolower(trim((string)($row[$colMap['correct_answer']] ?? 'a'))) : 'a';
                if ($rawAns === '1' || $rawAns === 'a' || str_starts_with($rawAns, 'opt a')) {
                    $correctOption = 'a';
                } elseif ($rawAns === '2' || $rawAns === 'b' || str_starts_with($rawAns, 'opt b')) {
                    $correctOption = 'b';
                } elseif ($rawAns === '3' || $rawAns === 'c' || str_starts_with($rawAns, 'opt c')) {
                    $correctOption = 'c';
                } elseif ($rawAns === '4' || $rawAns === 'd' || str_starts_with($rawAns, 'opt d')) {
                    $correctOption = 'd';
                } else {
                    $correctOption = in_array($rawAns, ['a', 'b', 'c', 'd']) ? $rawAns : 'a';
                }

                $marks = isset($colMap['marks']) && is_numeric($row[$colMap['marks']] ?? null) 
                    ? (float)$row[$colMap['marks']] 
                    : 1.0;

                $negMarks = isset($colMap['negative_marks']) && is_numeric($row[$colMap['negative_marks']] ?? null) 
                    ? (float)$row[$colMap['negative_marks']] 
                    : 0.0;

                $explanation = isset($colMap['explanation']) ? trim((string)($row[$colMap['explanation']] ?? '')) : null;
                if ($explanation === '') $explanation = null;

                Question::create([
                    'test_id' => $test->id,
                    'subject_id' => $test->subject_id ?? null,
                    'question_text' => $qText,
                    'options' => $options,
                    'correct_option' => $correctOption,
                    'marks' => $marks,
                    'negative_marks' => $negMarks,
                    'explanation' => $explanation,
                ]);

                $importedCount++;
            }

            if ($importedCount === 0) {
                return redirect()->back()->with('error', 'No valid questions could be imported. Please verify that Question Text, Option A, Option B, and Correct Answer are filled in your file.');
            }

            $message = "Successfully imported {$importedCount} questions from Excel!";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} blank or incomplete rows were skipped).";
            }

            return redirect()->route('admin.tests.questions.index', $test)->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error reading Excel file: ' . $e->getMessage());
        }
    }
}
