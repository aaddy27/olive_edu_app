<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Msg91Service
{
    protected string $authKey;
    protected string $templateId;
    protected string $senderId;
    protected int $otpExpiry;

    public function __construct()
    {
        $this->authKey = config('services.msg91.auth_key', env('MSG91_AUTH_KEY', ''));
        $this->templateId = config('services.msg91.template_id', env('MSG91_OTP_TEMPLATE_ID', '6b066cfc5a4ec8e5a80b3312'));
        $this->senderId = config('services.msg91.sender_id', env('MSG91_SENDER_ID', 'CIS015'));
        $this->otpExpiry = (int) config('services.msg91.otp_expiry', env('MSG91_OTP_EXPIRY', 10));
    }

    /**
     * Format mobile number to international format with country code (defaults to 91 for India)
     */
    public function formatMobile(string $mobile): string
    {
        // Strip non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $mobile);

        // If 10 digits (Indian mobile number), prefix with 91
        if (strlen($cleaned) === 10) {
            return '91' . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Send OTP using MSG91 Flow API (Template ID: 6b066cfc5a4ec8e5a80b3312)
     * Template: Dear User, Your OTP for login to Olive Learnings is ##var1##...
     */
    public function sendOtp(string $mobile, string $otp): array
    {
        $formattedNumber = $this->formatMobile($mobile);

        if (empty($this->authKey)) {
            Log::warning('MSG91_AUTH_KEY is not configured in .env. Skipping SMS dispatch.', [
                'mobile' => $formattedNumber,
                'otp' => $otp
            ]);
            return [
                'success' => false,
                'message' => 'MSG91_AUTH_KEY is not configured in .env',
                'otp' => $otp
            ];
        }

        $payload = [
            'template_id' => $this->templateId,
            'short_url' => '0',
            'recipients' => [
                [
                    'mobiles' => $formattedNumber,
                    'var1' => (string) $otp,
                ]
            ]
        ];

        if (!empty($this->senderId)) {
            $payload['sender'] = $this->senderId;
        }

        try {
            $response = Http::withHeaders([
                'authkey' => $this->authKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->timeout(10)->post('https://control.msg91.com/api/v5/flow', $payload);

            $body = $response->json();

            Log::info('MSG91 Send OTP response', [
                'mobile' => $formattedNumber,
                'status' => $response->status(),
                'response' => $body
            ]);

            if ($response->successful() && isset($body['type']) && $body['type'] === 'success') {
                return [
                    'success' => true,
                    'message' => 'OTP sent successfully via MSG91',
                    'data' => $body
                ];
            }

            return [
                'success' => false,
                'message' => $body['message'] ?? 'Failed to send OTP via MSG91',
                'data' => $body
            ];

        } catch (\Exception $e) {
            Log::error('MSG91 Send OTP Exception: ' . $e->getMessage(), [
                'mobile' => $formattedNumber,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Exception while sending OTP: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send OTP using MSG91 SendOTP API (/api/v5/otp)
     */
    public function sendOtpV5(string $mobile, ?string $otp = null): array
    {
        $formattedNumber = $this->formatMobile($mobile);

        if (empty($this->authKey)) {
            return [
                'success' => false,
                'message' => 'MSG91_AUTH_KEY is not configured in .env'
            ];
        }

        $queryParams = [
            'template_id' => $this->templateId,
            'mobile' => $formattedNumber,
            'otp_expiry' => $this->otpExpiry,
        ];

        if ($otp) {
            $queryParams['otp'] = $otp;
        }

        try {
            $response = Http::withHeaders([
                'authkey' => $this->authKey,
                'content-type' => 'application/json',
            ])->timeout(10)->post('https://control.msg91.com/api/v5/otp?' . http_build_query($queryParams), [
                'var1' => $otp ?? '',
                'Param1' => $otp ?? '',
            ]);

            $body = $response->json();

            if ($response->successful() && isset($body['type']) && $body['type'] === 'success') {
                return [
                    'success' => true,
                    'message' => 'OTP sent successfully',
                    'data' => $body
                ];
            }

            return [
                'success' => false,
                'message' => $body['message'] ?? 'Failed to send OTP',
                'data' => $body
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify OTP using MSG91 Verify OTP API (/api/v5/otp/verify)
     */
    public function verifyOtpV5(string $mobile, string $otp): array
    {
        $formattedNumber = $this->formatMobile($mobile);

        if (empty($this->authKey)) {
            return [
                'success' => false,
                'message' => 'MSG91_AUTH_KEY is not configured in .env'
            ];
        }

        try {
            $response = Http::withHeaders([
                'authkey' => $this->authKey,
            ])->timeout(10)->get('https://control.msg91.com/api/v5/otp/verify', [
                'mobile' => $formattedNumber,
                'otp' => $otp,
            ]);

            $body = $response->json();

            if ($response->successful() && isset($body['type']) && $body['type'] === 'success') {
                return [
                    'success' => true,
                    'message' => 'OTP verified successfully',
                    'data' => $body
                ];
            }

            return [
                'success' => false,
                'message' => $body['message'] ?? 'Invalid or expired OTP',
                'data' => $body
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}
