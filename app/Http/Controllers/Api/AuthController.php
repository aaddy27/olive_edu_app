<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Msg91Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user - Step 1: Send OTP
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[6-9][0-9]{9}$/|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9',
            'phone.unique' => 'This mobile number is already registered'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Store registration data temporarily in session/cache
            \App\Models\Otp::where('phone', $request->phone)->delete(); // Delete old OTPs
            
            $otpCode = str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $otpRecord = \App\Models\Otp::create([
                'phone' => $request->phone,
                'otp' => $otpCode,
                'expires_at' => now()->addMinutes(10),
            ]);

            // Store registration data in cache for 10 minutes
            \Cache::put('registration_' . $request->phone, [
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => $request->password,
            ], now()->addMinutes(10));

            // Send OTP via MSG91
            $msg91Service = app(Msg91Service::class);
            $msg91Service->sendOtp($request->phone, $otpCode);

            $responseData = [
                'phone' => $request->phone,
                'expires_in' => '10 minutes'
            ];
            if (config('app.debug') || empty(config('services.msg91.auth_key'))) {
                $responseData['otp'] = $otpCode;
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your mobile number. Please verify to complete registration.',
                'data' => $responseData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register - Step 2: Verify OTP and Complete Registration
     */
    public function verifyRegistrationOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^[6-9][0-9]{9}$/',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if OTP exists and is valid
            $otpRecord = \App\Models\Otp::where('phone', $request->phone)
                ->where('otp', $request->otp)
                ->where('is_verified', false)
                ->where('expires_at', '>', now())
                ->first();

            if (!$otpRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired OTP. Please try again.'
                ], 400);
            }

            // Get registration data from cache
            $registrationData = \Cache::get('registration_' . $request->phone);

            if (!$registrationData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration session expired. Please register again.'
                ], 400);
            }

            // Check if user already exists (double check)
            if (User::where('phone', $request->phone)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This mobile number is already registered.'
                ], 400);
            }

            // Create user
            $email = 'user' . $request->phone . '@eduapp.com';
            
            $user = User::create([
                'name' => $registrationData['name'],
                'email' => $email,
                'phone' => $request->phone,
                'password' => Hash::make($registrationData['password']),
                'role' => 'student',
                'is_active' => true,
            ]);

            // Mark OTP as verified
            $otpRecord->markAsVerified();

            // Clear cache
            \Cache::forget('registration_' . $request->phone);

            // Auto login after registration
            $token = $user->createToken('mobile-app')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! You are now logged in.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'role' => $user->role,
                    ],
                    'token' => $token
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'OTP verification failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login with phone and password
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number not registered'
            ], 404);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact support.'
            ], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password'
            ], 401);
        }

        // Revoke old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'city' => $user->city,
                    'state' => $user->state,
                    'role' => $user->role,
                ],
                'token' => $token
            ]
        ], 200);
    }

    /**
     * Send OTP for Login
     */
    public function sendLoginOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^[6-9][0-9]{9}$/|exists:users,phone',
        ], [
            'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9',
            'phone.exists' => 'This mobile number is not registered. Please register first.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact support.'
            ], 403);
        }

        // Generate 6-digit OTP
        $otpCode = str_pad((string)random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        \App\Models\Otp::where('phone', $request->phone)->delete();
        \App\Models\Otp::create([
            'phone' => $request->phone,
            'otp' => $otpCode,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via MSG91
        $msg91Service = app(Msg91Service::class);
        $msg91Service->sendOtp($request->phone, $otpCode);

        $responseData = [
            'phone' => $request->phone,
            'expires_in' => '10 minutes'
        ];
        if (config('app.debug') || empty(config('services.msg91.auth_key'))) {
            $responseData['otp'] = $otpCode;
        }

        return response()->json([
            'success' => true,
            'message' => 'Login OTP sent successfully to your mobile number.',
            'data' => $responseData
        ], 200);
    }

    /**
     * Verify Login OTP and issue Sanctum token
     */
    public function verifyLoginOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^[6-9][0-9]{9}$/',
            'otp' => 'required|string|min:4|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $otpRecord = \App\Models\Otp::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP. Please try again.'
            ], 400);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number not registered.'
            ], 404);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact support.'
            ], 403);
        }

        $otpRecord->markAsVerified();

        // Revoke old tokens & create new token
        $user->tokens()->delete();
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'city' => $user->city,
                    'state' => $user->state,
                    'role' => $user->role,
                ],
                'token' => $token
            ]
        ], 200);
    }

    /**
     * Send OTP for forgot password
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number not registered'
            ], 404);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact support.'
            ], 403);
        }

        // Generate 6-digit OTP
        $otp = (string) rand(100000, 999999);
        
        // Store OTP in database with expiry (10 minutes)
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send OTP via MSG91
        $msg91Service = app(Msg91Service::class);
        $msg91Service->sendOtp($user->phone, $otp);

        $responseData = [
            'phone' => $user->phone,
            'expires_in' => '10 minutes'
        ];
        if (config('app.debug') || empty(config('services.msg91.auth_key'))) {
            $responseData['otp'] = $otp;
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully to your mobile number',
            'data' => $responseData
        ], 200);
    }

    /**
     * Verify OTP for forgot password
     */
    public function verifyOtpForReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
            'otp' => 'required|string|min:4|max:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid mobile number or account inactive'
            ], 403);
        }

        // Check if OTP exists
        if (!$user->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Please request OTP first'
            ], 400);
        }

        // Check if OTP expired
        if (now()->greaterThan($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ], 401);
        }

        // Verify OTP
        if ($request->otp !== $user->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ], 401);
        }

        // Generate reset token (valid for 15 minutes)
        $resetToken = bin2hex(random_bytes(32));
        
        $user->update([
            'reset_token' => $resetToken,
            'reset_token_expires_at' => now()->addMinutes(15),
            'otp' => null, // Clear OTP after verification
            'otp_expires_at' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
            'data' => [
                'reset_token' => $resetToken,
                'expires_in' => '15 minutes'
            ]
        ], 200);
    }

    /**
     * Reset password using reset token
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
            'reset_token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone', $request->phone)
                    ->where('reset_token', $request->reset_token)
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reset token'
            ], 401);
        }

        // Check if reset token expired
        if (now()->greaterThan($user->reset_token_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Reset token has expired. Please request a new OTP.'
            ], 401);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'reset_token_expires_at' => null
        ]);

        // Revoke all existing tokens for security
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully. Please login with your new password.'
        ], 200);
    }

    /**
     * Change password (for logged-in users)
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 401);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Revoke all tokens except current one
        $currentToken = $request->user()->currentAccessToken();
        $user->tokens()->where('id', '!=', $currentToken->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ], 200);
    }

    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'city' => $user->city,
                    'state' => $user->state,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ]
            ]
        ], 200);
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only(['name', 'email', 'city', 'state']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'city' => $user->city,
                    'state' => $user->state,
                ]
            ]
        ], 200);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices successfully'
        ], 200);
    }
}

