<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestAttempt;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestAttemptController extends Controller
{
    protected TestService $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    /**
     * Start a test attempt
     */
    public function start($id, Request $request)
    {
        $test = Test::findOrFail($id);
        $user = Auth::user();

        try {
            $attempt = $this->testService->startTest($test, $user);
            return response()->json([
                'success' => true,
                'message' => 'Test started successfully',
                'data' => [
                    'attempt' => $attempt,
                    'questions' => $test->questions()->select('id', 'test_id', 'question_text', 'question_image', 'options', 'marks', 'negative_marks')->get()
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }

    /**
     * Save answer for an ongoing attempt
     */
    public function saveAnswer($id, Request $request)
    {
        $request->validate([
            'question_id' => 'required|integer',
            'answer' => 'required|string',
        ]);

        $attempt = TestAttempt::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $this->testService->saveAnswer($attempt, $request->question_id, $request->answer);
            return response()->json([
                'success' => true,
                'message' => 'Answer saved successfully',
                'data' => [
                    'attempt_id' => $attempt->id,
                    'answers' => $attempt->fresh()->answers
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Finish test and calculate results
     */
    public function finish($id, Request $request)
    {
        $attempt = TestAttempt::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $results = $this->testService->finishTest($attempt);
            return response()->json([
                'success' => true,
                'message' => 'Test submitted successfully',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get report / detailed scorecard for an attempt
     */
    public function report($id)
    {
        $attempt = TestAttempt::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($attempt->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'This test attempt is not completed yet.'
            ], 400);
        }

        $test = $attempt->test;
        $questions = $test->questions;
        $answers = $attempt->answers ?? [];

        $correctCount = 0;
        $incorrectCount = 0;
        $unansweredCount = 0;
        $detailedResults = [];

        foreach ($questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = false;

            if ($userAnswer === null) {
                $unansweredCount++;
            } else {
                $isCorrect = $question->isCorrect($userAnswer);
                if ($isCorrect) {
                    $correctCount++;
                } else {
                    $incorrectCount++;
                }
            }

            $detailedResults[] = [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'options' => $question->options,
                'user_answer' => $userAnswer,
                'correct_answer' => $question->correct_option,
                'is_correct' => $isCorrect,
                'marks_obtained' => $isCorrect ? $question->marks : ($userAnswer !== null ? -$question->negative_marks : 0),
                'explanation' => $question->explanation,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'attempt_id' => $attempt->id,
                'test_title' => $test->title,
                'score' => $attempt->score,
                'total_marks' => $test->total_marks,
                'total_questions' => $questions->count(),
                'correct_count' => $correctCount,
                'incorrect_count' => $incorrectCount,
                'unanswered_count' => $unansweredCount,
                'started_at' => $attempt->started_at,
                'finished_at' => $attempt->finished_at,
                'detailed_results' => $detailedResults
            ]
        ]);
    }

    /**
     * Get all test attempts for the authenticated user
     */
    public function myAttempts(Request $request)
    {
        $user = Auth::user();
        $attempts = TestAttempt::where('user_id', $user->id)
            ->with(['test:id,title,total_marks,passing_marks,duration_minutes'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $attempts
        ]);
    }
}
