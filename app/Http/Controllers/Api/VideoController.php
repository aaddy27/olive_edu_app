<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Purchase;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    /**
     * Stream video (protected - check if user can access)
     */
    public function stream(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $video = Video::forStudents()->with('course')->findOrFail($id);

            // If video is free, anyone can watch (even without purchase)
            if ($video->is_free) {
                return response()->json([
                    'success' => true,
                    'message' => 'Video access granted (Free video)',
                    'data' => [
                        'video' => [
                            'id' => $video->id,
                            'title' => $video->title,
                            'description' => $video->description,
                            'video_url' => $video->video_url,
                            'vimeo_video_id' => $video->vimeo_video_id,
                            'duration_seconds' => $video->duration_seconds,
                            'is_free' => true,
                            'can_play' => true
                        ]
                    ]
                ]);
            }

            // For paid videos, check if user has purchased the course
            if ($video->course_id) {
                $hasPurchased = Purchase::where('user_id', $user->id)
                    ->where('purchasable_type', Course::class)
                    ->where('purchasable_id', $video->course_id)
                    ->where('payment_status', 'completed')
                    ->exists();

                if (!$hasPurchased) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You need to purchase this course to watch this video',
                        'data' => [
                            'course_id' => $video->course_id,
                            'course_title' => $video->course->title ?? null,
                            'requires_purchase' => true
                        ]
                    ], 403);
                }
            }

            // User has purchased or video is standalone paid, grant access
            return response()->json([
                'success' => true,
                'message' => 'Video access granted',
                'data' => [
                    'video' => [
                        'id' => $video->id,
                        'title' => $video->title,
                        'description' => $video->description,
                        'video_url' => $video->video_url,
                        'vimeo_video_id' => $video->vimeo_video_id,
                        'duration_seconds' => $video->duration_seconds,
                        'is_free' => false,
                        'can_play' => true
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load video',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
