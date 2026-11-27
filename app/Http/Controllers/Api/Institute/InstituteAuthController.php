<?php

namespace App\Http\Controllers\Api\Institute;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class InstituteAuthController extends Controller
{
    /**
     * Institute Admin Login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string', // Email or Phone
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $login = $request->login;

        // Find user by email or phone
        $user = User::with('institute')
            ->where(function ($q) use ($login) {
                $q->where('email', $login)
                  ->orWhere('phone', $login);
            })
            ->where('role', 'institute_admin')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials or you do not have institute administrator access.',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your administrator account is inactive. Please contact support.',
            ], 403);
        }

        if (!$user->institute_id || !$user->institute) {
            return response()->json([
                'success' => false,
                'message' => 'No institute is associated with this administrator account.',
            ], 403);
        }

        if (!$user->institute->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your institute account has been deactivated. Please contact support.',
            ], 403);
        }

        if ($user->institute->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Your institute subscription/validity has expired. Please contact administration.',
            ], 403);
        }

        // Generate Sanctum access token
        $token = $user->createToken('institute_admin_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'designation' => $user->designation,
                ],
                'institute' => [
                    'id' => $user->institute->id,
                    'name' => $user->institute->name,
                    'code' => $user->institute->code,
                    'email' => $user->institute->email,
                    'phone' => $user->institute->phone,
                    'city' => $user->institute->city,
                    'state' => $user->institute->state,
                    'logo_url' => $user->institute->logo_url,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * Get Institute Admin Profile & Institute Info
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load('institute');
        $institute = $user->institute;

        $totalTeachers = User::where('institute_id', $institute->id)
            ->where('role', 'teacher')
            ->count();

        $activeTeachers = User::where('institute_id', $institute->id)
            ->where('role', 'teacher')
            ->where('is_active', true)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'admin' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'designation' => $user->designation,
                    'created_at' => $user->created_at,
                ],
                'institute' => [
                    'id' => $institute->id,
                    'name' => $institute->name,
                    'code' => $institute->code,
                    'email' => $institute->email,
                    'phone' => $institute->phone,
                    'address' => $institute->address,
                    'city' => $institute->city,
                    'state' => $institute->state,
                    'pincode' => $institute->pincode,
                    'logo_url' => $institute->logo_url,
                    'is_active' => $institute->is_active,
                ],
                'stats' => [
                    'total_teachers' => $totalTeachers,
                    'active_teachers' => $activeTeachers,
                ],
            ],
        ]);
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|nullable|string|max:15|unique:users,phone,' . $user->id,
            'password' => 'sometimes|nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }
}
