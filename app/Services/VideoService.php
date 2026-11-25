<?php

namespace App\Services;

use App\Models\Video;
use App\Models\User;
use App\Models\VideoViewLimit;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class VideoService
{
    protected $vimeoAccessToken;

    public function __construct()
    {
        $this->vimeoAccessToken = config('services.vimeo.access_token');
    }

    /**
     * Get streaming URL for video
     */
    public function getStreamingUrl(Video $video, User $user): array
    {
        // Check if user can view the video
        if (!$video->canView($user)) {
            throw new \Exception('You do not have access to this video');
        }

        // Check view limit
        if (!$this->checkViewLimit($video, $user)) {
            throw new \Exception('You have exceeded the maximum views for this video');
        }

        // Increment view count
        $this->incrementViewCount($video, $user);

        // Get streaming URL based on provider
        if ($video->vimeo_video_id) {
            return $this->getVimeoStreamingUrl($video);
        }

        if ($video->s3_key) {
            return $this->getS3StreamingUrl($video);
        }

        throw new \Exception('Video source not available');
    }

    /**
     * Get Vimeo streaming URL
     */
    protected function getVimeoStreamingUrl(Video $video): array
    {
        // TODO: Implement Vimeo API integration
        // Example: Use Vimeo API to get secure playback URL
        
        // In production:
        // $response = Http::withToken($this->vimeoAccessToken)
        //     ->get("https://api.vimeo.com/videos/{$video->vimeo_video_id}");
        
        // For development:
        return [
            'url' => "https://player.vimeo.com/video/{$video->vimeo_video_id}",
            'type' => 'vimeo',
            'expires_at' => now()->addHours(2)->toIso8601String(),
        ];
    }

    /**
     * Get S3 streaming URL (presigned)
     */
    protected function getS3StreamingUrl(Video $video): array
    {
        $url = Storage::disk('s3')->temporaryUrl(
            $video->s3_key,
            now()->addHours(2)
        );

        return [
            'url' => $url,
            'type' => 's3',
            'expires_at' => now()->addHours(2)->toIso8601String(),
        ];
    }

    /**
     * Check if user has views remaining
     */
    protected function checkViewLimit(Video $video, User $user): bool
    {
        // Preview videos don't have limits
        if ($video->is_preview) {
            return true;
        }

        // Get or create view limit record
        $viewLimit = VideoViewLimit::firstOrCreate(
            [
                'video_id' => $video->id,
                'user_id' => $user->id,
            ],
            [
                'max_views' => Setting::get('default_video_view_limit', 3),
                'viewed_count' => 0,
            ]
        );

        return $viewLimit->hasViewsRemaining();
    }

    /**
     * Increment view count
     */
    protected function incrementViewCount(Video $video, User $user): void
    {
        $viewLimit = VideoViewLimit::where('video_id', $video->id)
            ->where('user_id', $user->id)
            ->first();

        if ($viewLimit) {
            $viewLimit->incrementView();
        }
    }

    /**
     * Upload video to Vimeo
     */
    public function uploadToVimeo(string $filePath, array $metadata = []): string
    {
        // TODO: Implement Vimeo upload
        // This would use Vimeo's upload API
        
        // Return Vimeo video ID
        return 'vimeo_' . uniqid();
    }

    /**
     * Upload video to S3
     */
    public function uploadToS3(string $filePath, string $fileName): string
    {
        $path = 'videos/' . date('Y/m/d') . '/' . $fileName;
        
        Storage::disk('s3')->putFileAs(
            'videos/' . date('Y/m/d'),
            $filePath,
            $fileName,
            'private'
        );

        return $path;
    }

    /**
     * Process video (transcode, generate thumbnails, etc.)
     */
    public function processVideo(Video $video): void
    {
        // This would be dispatched as a job
        // For now, just update status
        $video->update(['status' => 'processing']);

        // TODO: Implement video processing
        // - Transcode to multiple qualities
        // - Generate thumbnails
        // - Extract duration
        // - Update video record

        $video->update(['status' => 'ready']);
    }
}
