<?php

namespace App\Services;

use App\Models\Test;
use App\Models\TestAttempt;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TestService
{
    /**
     * Start a new test attempt
     */
    public function startTest(Test $test, User $user): TestAttempt
    {
        // Check if user has access to this test
        if ($test->price > 0) {
            if (!$user->hasPurchased(Test::class, $test->id)) {
                throw new \Exception('You need to purchase this test first');
            }
        }

        // Create test attempt
        $attempt = TestAttempt::create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'started_at' => now(),
            'status' => 'started',
        ]);

        // Get questions (without correct answers)
        $questions = $test->questions()
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'options' => $question->options,
                    'marks' => $question->marks,
                ];
            });

        return $attempt->load('test');
    }

    /**
     * Save answer for a question
     */
    public function saveAnswer(TestAttempt $attempt, int $questionId, string $answer): void
    {
        if ($attempt->status === 'completed') {
            throw new \Exception('Test has already been completed');
        }

        $answers = $attempt->answers ?? [];
        $answers[$questionId] = $answer;

        $attempt->update([
            'answers' => $answers,
            'status' => 'in_progress',
        ]);
    }

    /**
     * Finish test and calculate score
     */
    public function finishTest(TestAttempt $attempt): array
    {
        if ($attempt->status === 'completed') {
            throw new \Exception('Test has already been completed');
        }

        $test = $attempt->test;
        $questions = $test->questions;
        $answers = $attempt->answers ?? [];

        $score = 0;
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
                    $score += $question->marks;
                    $correctCount++;
                } else {
                    $score -= $question->negative_marks;
                    $incorrectCount++;
                }
            }

            $detailedResults[] = [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'user_answer' => $userAnswer,
                'correct_answer' => $question->correct_option,
                'is_correct' => $isCorrect,
                'marks_obtained' => $isCorrect ? $question->marks : -$question->negative_marks,
                'explanation' => $question->explanation,
            ];
        }

        // Update attempt
        $attempt->update([
            'finished_at' => now(),
            'score' => $score,
            'status' => 'completed',
        ]);

        return [
            'attempt_id' => $attempt->id,
            'score' => $score,
            'total_questions' => $test->total_questions,
            'correct_count' => $correctCount,
            'incorrect_count' => $incorrectCount,
            'unanswered_count' => $unansweredCount,
            'duration_minutes' => $attempt->getDuration(),
            'detailed_results' => $detailedResults,
        ];
    }

    /**
     * Get test report
     */
    public function getTestReport(TestAttempt $attempt): array
    {
        if ($attempt->status !== 'completed') {
            throw new \Exception('Test is not completed yet');
        }

        return $this->finishTest($attempt);
    }

    /**
     * Get user's test history
     */
    public function getUserTestHistory(User $user, ?int $testId = null): array
    {
        $query = TestAttempt::where('user_id', $user->id)
            ->with(['test'])
            ->orderBy('created_at', 'desc');

        if ($testId) {
            $query->where('test_id', $testId);
        }

        return $query->get()->map(function ($attempt) {
            return [
                'id' => $attempt->id,
                'test' => [
                    'id' => $attempt->test->id,
                    'title' => $attempt->test->title,
                ],
                'score' => $attempt->score,
                'status' => $attempt->status,
                'started_at' => $attempt->started_at,
                'finished_at' => $attempt->finished_at,
                'duration_minutes' => $attempt->getDuration(),
            ];
        })->toArray();
    }
}
