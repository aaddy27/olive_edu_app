<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Get all courses (public)
     */
    public function index(Request $request)
    {
        try {
            $query = Course::with(['examBoard', 'subjects', 'videos'])
                ->published();

            // Filter by exam_id
            if ($request->has('exam_id')) {
                $query->whereHas('subjects', function ($q) use ($request) {
                    $q->where('exam_id', $request->exam_id);
                });
            }

            // Filter by subject_id
            if ($request->has('subject_id')) {
                $query->whereHas('subjects', function ($q) use ($request) {
                    $q->where('subjects.id', $request->subject_id);
                });
            }

            // Search by title
            if ($request->has('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            $courses = $query->latest()->get();

            // Add video count for each subject in each course
            $coursesData = $courses->map(function($course) {
                $courseArray = $course->toArray();
                
                if (isset($courseArray['subjects']) && is_array($courseArray['subjects'])) {
                    $courseArray['subjects'] = collect($courseArray['subjects'])->map(function($subject) use ($course) {
                        // Count videos for this subject in this course
                        $videoCount = $course->videos()->where('subject_id', $subject['id'])->count();
                        $subject['videos_count'] = $videoCount;
                        return $subject;
                    })->toArray();
                }
                
                return $courseArray;
            });

            return response()->json([
                'success' => true,
                'message' => 'Courses retrieved successfully',
                'data' => $coursesData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course by slug (public)
     */
    public function show($slug)
    {
        try {
            $course = Course::with(['examBoard', 'subjects', 'videos'])
                ->published()
                ->where('slug', $slug)
                ->firstOrFail();

            // Add video count for each subject
            $courseData = $course->toArray();
            
            if (isset($courseData['subjects']) && is_array($courseData['subjects'])) {
                $courseData['subjects'] = collect($courseData['subjects'])->map(function($subject) use ($course) {
                    // Count videos for this subject in this course
                    $videoCount = $course->videos()->where('subject_id', $subject['id'])->count();
                    $subject['videos_count'] = $videoCount;
                    return $subject;
                })->toArray();
            }

            return response()->json([
                'success' => true,
                'message' => 'Course retrieved successfully',
                'data' => $courseData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get my purchased courses (protected)
     */
    public function myCourses(Request $request)
    {
        try {
            $user = Auth::user();

            $purchases = Purchase::with(['purchasable.examBoard', 'purchasable.subjects'])
                ->where('user_id', $user->id)
                ->where('purchasable_type', Course::class)
                ->where('payment_status', 'completed')
                ->latest()
                ->get();

            $courses = $purchases->map(function ($purchase) {
                $course = $purchase->purchasable;
                $course->purchased_at = $purchase->created_at;
                return $course;
            });

            return response()->json([
                'success' => true,
                'message' => 'My courses retrieved successfully',
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course videos (public - but includes purchase status)
     */
    public function videos(Request $request, $id)
    {
        try {
            $course = Course::with(['videos.subject'])->findOrFail($id);

            // Check if user is logged in and has purchased this course
            $hasPurchased = false;
            if ($request->user()) {
                $hasPurchased = Purchase::where('user_id', $request->user()->id)
                    ->where('purchasable_type', Course::class)
                    ->where('purchasable_id', $id)
                    ->where('payment_status', 'completed')
                    ->exists();
            }

            return response()->json([
                'success' => true,
                'message' => 'Course videos retrieved successfully',
                'data' => [
                    'course' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'price' => $course->price,
                        'has_purchased' => $hasPurchased
                    ],
                    'videos' => $course->videos->map(function($video) use ($hasPurchased) {
                        return [
                            'id' => $video->id,
                            'title' => $video->title,
                            'description' => $video->description,
                            'video_url' => $video->video_url,
                            'vimeo_video_id' => $video->vimeo_video_id,
                            'duration_seconds' => $video->duration_seconds,
                            'view_limit' => $video->view_limit,
                            'sort_order' => $video->sort_order,
                            'is_preview' => $video->is_preview,
                            'is_free' => $video->is_free,
                            'is_active' => $video->is_active,
                            'status' => $video->status,
                            'can_play' => $video->is_free || $hasPurchased, // User can play if free or purchased
                            'subject' => $video->subject ? [
                                'id' => $video->subject->id,
                                'name' => $video->subject->name
                            ] : null,
                            'created_at' => $video->created_at,
                            'updated_at' => $video->updated_at
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch videos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
