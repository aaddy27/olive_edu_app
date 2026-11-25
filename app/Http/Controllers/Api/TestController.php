<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    /**
     * Get all test series (list view)
     * Shows only parent test series (is_test_series = true AND parent_id = null)
     */
    public function index(Request $request)
    {
        $query = Test::where('is_test_series', true)
            ->whereNull('parent_id') // Only parent test series, not child tests
            ->where('is_active', true)
            ->with(['course:id,title', 'subjects:id,name'])
            ->withCount('questions');

        // Optional filters
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $testSeries = $query->latest()->paginate(20);

        // Check purchase status for authenticated users
        $user = Auth::user();
        $testSeries->getCollection()->transform(function ($test) use ($user) {
            $test->is_purchased = false;
            $test->is_accessible = $test->is_free || $test->price == 0;

            if ($user) {
                $test->is_purchased = Purchase::where('user_id', $user->id)
                    ->where('purchasable_type', 'App\Models\Test')
                    ->where('purchasable_id', $test->id)
                    ->where('status', 'completed')
                    ->exists();

                $test->is_accessible = $test->is_purchased || $test->is_free || $test->price == 0;
            }

            return $test;
        });

        return response()->json([
            'success' => true,
            'data' => $testSeries
        ]);
    }

    /**
     * Get test series detail with subjects
     * Shows all subjects in the test series
     */
    public function show($id, Request $request)
    {
        $test = Test::where('id', $id)
            ->where('is_test_series', true)
            ->where('is_active', true)
            ->with(['course:id,title', 'subjects'])
            ->first();

        if (!$test) {
            return response()->json([
                'success' => false,
                'message' => 'Test series not found'
            ], 404);
        }

        $user = Auth::user();
        $test->is_purchased = false;
        $test->is_accessible = $test->is_free || $test->price == 0;

        if ($user) {
            $test->is_purchased = Purchase::where('user_id', $user->id)
                ->where('purchasable_type', 'App\Models\Test')
                ->where('purchasable_id', $test->id)
                ->where('status', 'completed')
                ->exists();

            $test->is_accessible = $test->is_purchased || $test->is_free || $test->price == 0;
        }

        // Add question count per subject
        $test->subjects->each(function ($subject) use ($test) {
            $subject->question_count = Question::where('test_id', $test->id)
                ->where('subject_id', $subject->id)
                ->count();
        });

        return response()->json([
            'success' => true,
            'data' => $test
        ]);
    }

    /**
     * Get subject-wise questions for a test series
     * Only accessible if purchased or free
     */
    public function testQuestions($testId, Request $request)
    {
        $test = Test::where('id', $testId)
            ->where('is_active', true)
            ->with(['parent', 'subject'])
            ->first();

        if (!$test) {
            return response()->json([
                'success' => false,
                'message' => 'Test not found'
            ], 404);
        }

        // Find parent test for access check
        $parentTest = $test->parent_id ? $test->parent : $test;

        // Check access
        $user = Auth::user();
        $isAccessible = $parentTest->is_free || $parentTest->price == 0;

        if ($user && !$isAccessible) {
            $isAccessible = Purchase::where('user_id', $user->id)
                ->where('purchasable_type', 'App\Models\Test')
                ->where('purchasable_id', $parentTest->id)
                ->where('status', 'completed')
                ->exists();
        }

        if (!$isAccessible) {
            return response()->json([
                'success' => false,
                'message' => 'Please purchase this test series to access the questions',
                'requires_purchase' => true
            ], 403);
        }

        // Get questions with full image URLs
        $questions = Question::where('test_id', $testId)
            ->select('id', 'question_text', 'question_image', 'options', 'marks', 'negative_marks')
            ->get()
            ->map(function ($question) {
                // Convert image path to full URL
                if ($question->question_image) {
                    $question->question_image_url = url($question->question_image);
                } else {
                    $question->question_image_url = null;
                }
                return $question;
            });

        return response()->json([
            'success' => true,
            'data' => [
                'test' => [
                    'id' => $test->id,
                    'title' => $test->title,
                    'test_number' => $test->test_number,
                    'duration' => $test->duration,
                    'total_marks' => $test->total_marks,
                    'negative_marking' => $test->negative_marking,
                    'instructions' => $test->instructions,
                    'parent_title' => $test->parent ? $test->parent->title : null,
                    'subject' => $test->subject ? $test->subject->name : null
                ],
                'questions' => $questions,
                'total_questions' => $questions->count()
            ]
        ]);
    }

    /**
     * Get all subjects with their test counts for a test series
     */
    public function subjects($testId)
    {
        $test = Test::where('id', $testId)
            ->where('is_test_series', true)
            ->where('is_active', true)
            ->whereNull('parent_id') // Only parent test series
            ->with('subjects')
            ->first();

        if (!$test) {
            return response()->json([
                'success' => false,
                'message' => 'Test series not found'
            ], 404);
        }

        // Check access for authenticated users
        $user = Auth::user();
        $isAccessible = $test->is_free || $test->price == 0;
        $isPurchased = false;

        if ($user) {
            $isPurchased = Purchase::where('user_id', $user->id)
                ->where('purchasable_type', 'App\Models\Test')
                ->where('purchasable_id', $test->id)
                ->where('status', 'completed')
                ->exists();
            $isAccessible = $isPurchased || $isAccessible;
        }

        // Get subjects with child test counts
        $subjects = $test->subjects->map(function ($subject) use ($test, $isAccessible) {
            $testCount = Test::where('parent_id', $test->id)
                ->where('subject_id', $subject->id)
                ->where('is_active', true)
                ->count();

            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'test_count' => $testCount,
                'is_accessible' => $isAccessible
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'test_id' => $test->id,
                'test_title' => $test->title,
                'is_purchased' => $isPurchased,
                'is_accessible' => $isAccessible,
                'price' => $test->price,
                'subjects' => $subjects
            ]
        ]);
    }

    /**
     * Get all tests for a specific subject in a test series
     */
    public function subjectTests($testId, $subjectId)
    {
        $parentTest = Test::where('id', $testId)
            ->where('is_test_series', true)
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->first();

        if (!$parentTest) {
            return response()->json([
                'success' => false,
                'message' => 'Test series not found'
            ], 404);
        }

        // Check access
        $user = Auth::user();
        $isAccessible = $parentTest->is_free || $parentTest->price == 0;

        if ($user && !$isAccessible) {
            $isAccessible = Purchase::where('user_id', $user->id)
                ->where('purchasable_type', 'App\Models\Test')
                ->where('purchasable_id', $parentTest->id)
                ->where('status', 'completed')
                ->exists();
        }

        if (!$isAccessible) {
            return response()->json([
                'success' => false,
                'message' => 'Please purchase this test series to access',
                'requires_purchase' => true
            ], 403);
        }

        // Get subject
        $subject = Subject::find($subjectId);
        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject not found'
            ], 404);
        }

        // Get child tests for this subject
        $tests = Test::where('parent_id', $testId)
            ->where('subject_id', $subjectId)
            ->where('is_active', true)
            ->withCount('questions')
            ->orderBy('test_number')
            ->get()
            ->map(function ($test) {
                return [
                    'id' => $test->id,
                    'title' => $test->title,
                    'test_number' => $test->test_number,
                    'duration' => $test->duration,
                    'total_marks' => $test->total_marks,
                    'passing_marks' => $test->passing_marks,
                    'negative_marking' => $test->negative_marking,
                    'questions_count' => $test->questions_count,
                    'instructions' => $test->instructions
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'test_series' => [
                    'id' => $parentTest->id,
                    'title' => $parentTest->title
                ],
                'subject' => [
                    'id' => $subject->id,
                    'name' => $subject->name
                ],
                'tests' => $tests
            ]
        ]);
    }

    /**
     * Debug endpoint to check database status
     * Remove this in production
     */
    public function debug()
    {
        $allTests = Test::select('id', 'title', 'is_test_series', 'is_active', 'parent_id', 'price')
            ->get();

        $testSeriesCount = Test::where('is_test_series', true)->whereNull('parent_id')->count();
        $activeTestSeriesCount = Test::where('is_test_series', true)->whereNull('parent_id')->where('is_active', true)->count();
        $childTestsCount = Test::whereNotNull('parent_id')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_tests' => $allTests->count(),
                'test_series_count' => $testSeriesCount,
                'active_test_series_count' => $activeTestSeriesCount,
                'child_tests_count' => $childTestsCount,
                'all_tests' => $allTests,
            ]
        ]);
    }
}
