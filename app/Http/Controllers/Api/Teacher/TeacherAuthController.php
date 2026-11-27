<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherAuthController extends Controller
{
    /**
     * Teacher Login
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

        $teacher = User::with('institute')
            ->where(function ($q) use ($login) {
                $q->where('email', $login)
                  ->orWhere('phone', $login);
            })
            ->where('role', 'teacher')
            ->first();

        if (!$teacher || !Hash::check($request->password, $teacher->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid teacher login credentials.',
            ], 401);
        }

        if (!$teacher->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your teacher account has been deactivated. Contact your Institute Administrator.',
            ], 403);
        }

        if (!$teacher->institute_id || !$teacher->institute) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not mapped to any institute.',
            ], 403);
        }

        if (!$teacher->institute->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your institute account is currently inactive.',
            ], 403);
        }

        if ($teacher->institute->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Your institute subscription/validity has expired. Please contact administration.',
            ], 403);
        }

        // Create API token for teacher
        $token = $teacher->createToken('teacher_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Teacher login successful',
            'data' => [
                'teacher' => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                    'phone' => $teacher->phone,
                    'role' => $teacher->role,
                    'designation' => $teacher->designation,
                    'employee_id' => $teacher->employee_id,
                ],
                'institute' => [
                    'id' => $teacher->institute->id,
                    'name' => $teacher->institute->name,
                    'code' => $teacher->institute->code,
                    'email' => $teacher->institute->email,
                    'phone' => $teacher->institute->phone,
                    'city' => $teacher->institute->city,
                    'state' => $teacher->institute->state,
                    'logo_url' => $teacher->institute->logo_url,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * Teacher Profile
     */
    public function profile(Request $request)
    {
        $teacher = $request->user()->load('institute');

        return response()->json([
            'success' => true,
            'data' => [
                'teacher' => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                    'phone' => $teacher->phone,
                    'role' => $teacher->role,
                    'designation' => $teacher->designation,
                    'employee_id' => $teacher->employee_id,
                    'city' => $teacher->city,
                    'state' => $teacher->state,
                    'created_at' => $teacher->created_at,
                ],
                'institute' => [
                    'id' => $teacher->institute->id,
                    'name' => $teacher->institute->name,
                    'code' => $teacher->institute->code,
                    'email' => $teacher->institute->email,
                    'phone' => $teacher->institute->phone,
                    'address' => $teacher->institute->address,
                    'city' => $teacher->institute->city,
                    'state' => $teacher->institute->state,
                    'logo_url' => $teacher->institute->logo_url,
                ],
            ],
        ]);
    }

    /**
     * Update Teacher Profile
     */
    public function updateProfile(Request $request)
    {
        $teacher = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|nullable|string|max:15|unique:users,phone,' . $teacher->id,
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
            $teacher->name = $request->name;
        }

        if ($request->has('phone')) {
            $teacher->phone = $request->phone;
        }

        if ($request->filled('password')) {
            $teacher->password = Hash::make($request->password);
        }

        $teacher->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'phone' => $teacher->phone,
            ],
        ]);
    }

    /**
     * Teacher Logout
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
