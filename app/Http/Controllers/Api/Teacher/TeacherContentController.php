<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Http\Request;

class TeacherContentController extends Controller
{
    /**
     * Get all published courses for teachers (Free access for all institute teachers)
     */
    public function courses(Request $request)
    {
        $query = Course::with(['examBoard', 'subjects'])
            ->withCount(['videos', 'playlists'])
            ->published();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('exam_board_id')) {
            $query->where('exam_board_id', $request->exam_board_id);
        }

        $courses = $query->latest()->get();

        $coursesData = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'thumbnail_url' => $course->thumbnail_url,
                'is_free' => true, // All courses free for institute teachers
                'exam_board' => $course->examBoard ? [
                    'id' => $course->examBoard->id,
                    'name' => $course->examBoard->name,
                ] : null,
                'subjects_count' => $course->subjects->count(),
                'playlists_count' => $course->playlists_count,
                'videos_count' => $course->videos_count,
                'subjects' => $course->subjects->map(function ($subject) use ($course) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'slug' => $subject->slug,
                        'videos_count' => $course->videos()->where('subject_id', $subject->id)->count(),
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Courses retrieved successfully',
            'data' => $coursesData,
        ]);
    }

    /**
     * Get detailed course information with subjects and playlists
     */
    public function courseDetails($id)
    {
        $course = Course::with(['examBoard', 'subjects', 'playlists.videos', 'videos' => function ($q) {
            $q->whereNull('playlist_id'); // Standalone videos
        }])
        ->where('is_published', true)
        ->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found or is unpublished.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'thumbnail_url' => $course->thumbnail_url,
                'is_free' => true,
                'exam_board' => $course->examBoard ? [
                    'id' => $course->examBoard->id,
                    'name' => $course->examBoard->name,
                ] : null,
                'subjects' => $course->subjects->map(function ($s) use ($course) {
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'slug' => $s->slug,
                        'description' => $s->description,
                        'playlists_count' => $course->playlists()->where('subject_id', $s->id)->count(),
                        'videos_count' => $course->videos()->where('subject_id', $s->id)->count(),
                    ];
                }),
                'playlists' => $course->playlists()->where('is_active', true)->withCount('videos')->get()->map(function ($pl) {
                    return [
                        'id' => $pl->id,
                        'subject_id' => $pl->subject_id,
                        'title' => $pl->title,
                        'slug' => $pl->slug,
                        'description' => $pl->description,
                        'thumbnail_url' => $pl->thumbnail_url,
                        'sort_order' => $pl->sort_order,
                        'videos_count' => $pl->videos_count,
                    ];
                }),
                'standalone_videos' => $course->videos->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'subject_id' => $v->subject_id,
                        'title' => $v->title,
                        'description' => $v->description,
                        'duration_seconds' => $v->duration_seconds,
                        'duration_formatted' => $v->getDurationFormatted(),
                        'sort_order' => $v->sort_order,
                        'video_url' => $v->video_url,
                        'vimeo_video_id' => $v->vimeo_video_id,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Get subject content within a course (playlists and videos)
     */
    public function subjectContent($courseId, $subjectId)
    {
        $course = Course::published()->find($courseId);
        if (!$course) {
            return response()->json(['success' => false, 'message' => 'Course not found'], 404);
        }

        $subject = Subject::find($subjectId);
        if (!$subject) {
            return response()->json(['success' => false, 'message' => 'Subject not found'], 404);
        }

        // Get playlists for this course and subject
        $playlists = Playlist::where('course_id', $courseId)
            ->where(function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId)
                  ->orWhereNull('subject_id');
            })
            ->where('is_active', true)
            ->with(['videos' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        // Get standalone videos for this course and subject
        $standaloneVideos = Video::where('course_id', $courseId)
            ->where('subject_id', $subjectId)
            ->whereNull('playlist_id')
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                ],
                'subject' => [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'description' => $subject->description,
                ],
                'playlists' => $playlists->map(function ($pl) {
                    return [
                        'id' => $pl->id,
                        'title' => $pl->title,
                        'description' => $pl->description,
                        'thumbnail_url' => $pl->thumbnail_url,
                        'videos' => $pl->videos->map(function ($v) {
                            return [
                                'id' => $v->id,
                                'title' => $v->title,
                                'description' => $v->description,
                                'duration_seconds' => $v->duration_seconds,
                                'duration_formatted' => $v->getDurationFormatted(),
                                'video_url' => $v->video_url,
                                'vimeo_video_id' => $v->vimeo_video_id,
                                'sort_order' => $v->sort_order,
                            ];
                        }),
                    ];
                }),
                'standalone_videos' => $standaloneVideos->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'title' => $v->title,
                        'description' => $v->description,
                        'duration_seconds' => $v->duration_seconds,
                        'duration_formatted' => $v->getDurationFormatted(),
                        'video_url' => $v->video_url,
                        'vimeo_video_id' => $v->vimeo_video_id,
                        'sort_order' => $v->sort_order,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Get details of a single playlist with all its videos
     */
    public function playlistDetails($id)
    {
        $playlist = Playlist::with(['course', 'subject', 'videos' => function ($q) {
            $q->orderBy('sort_order');
        }])
        ->where('is_active', true)
        ->find($id);

        if (!$playlist) {
            return response()->json([
                'success' => false,
                'message' => 'Playlist not found or is inactive.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $playlist->id,
                'title' => $playlist->title,
                'slug' => $playlist->slug,
                'description' => $playlist->description,
                'thumbnail_url' => $playlist->thumbnail_url,
                'course' => [
                    'id' => $playlist->course->id,
                    'title' => $playlist->course->title,
                ],
                'subject' => $playlist->subject ? [
                    'id' => $playlist->subject->id,
                    'name' => $playlist->subject->name,
                ] : null,
                'videos_count' => $playlist->videos->count(),
                'videos' => $playlist->videos->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'title' => $v->title,
                        'description' => $v->description,
                        'duration_seconds' => $v->duration_seconds,
                        'duration_formatted' => $v->getDurationFormatted(),
                        'video_url' => $v->video_url,
                        'vimeo_video_id' => $v->vimeo_video_id,
                        'sort_order' => $v->sort_order,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Stream / Watch Video (Free access for all teachers)
     */
    public function videoDetails($id)
    {
        $video = Video::with(['course', 'subject', 'playlist'])->find($id);

        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Video streaming details loaded.',
            'data' => [
                'id' => $video->id,
                'title' => $video->title,
                'description' => $video->description,
                'video_url' => $video->video_url,
                'vimeo_video_id' => $video->vimeo_video_id,
                'duration_seconds' => $video->duration_seconds,
                'duration_formatted' => $video->getDurationFormatted(),
                'course' => $video->course ? [
                    'id' => $video->course->id,
                    'title' => $video->course->title,
                ] : null,
                'subject' => $video->subject ? [
                    'id' => $video->subject->id,
                    'name' => $video->subject->name,
                ] : null,
                'playlist' => $video->playlist ? [
                    'id' => $video->playlist->id,
                    'title' => $video->playlist->title,
                ] : null,
                'is_free' => true,
            ],
        ]);
    }
}
