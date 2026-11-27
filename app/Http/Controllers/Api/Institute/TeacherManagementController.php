<?php

namespace App\Http\Controllers\Api\Institute;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeacherManagementController extends Controller
{
    /**
     * List all teachers under the authenticated institute
     */
    public function index(Request $request)
    {
        $instituteId = $request->user()->institute_id;

        $query = User::where('institute_id', $instituteId)
            ->where('role', 'teacher');

        // Search by name, email, phone, employee_id, designation
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->filled('status')) {
            $isActive = in_array(strtolower($request->status), ['1', 'true', 'active']);
            $query->where('is_active', $isActive);
        }

        $perPage = (int) $request->get('per_page', 15);
        $teachers = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'teachers' => $teachers->items(),
                'pagination' => [
                    'current_page' => $teachers->currentPage(),
                    'last_page' => $teachers->lastPage(),
                    'per_page' => $teachers->perPage(),
                    'total' => $teachers->total(),
                ],
            ],
        ]);
    }

    /**
     * Add / Register a new teacher under this institute
     */
    public function store(Request $request)
    {
        $instituteId = $request->user()->institute_id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:15|unique:users,phone',
            'password' => 'required|string|min:6',
            'designation' => 'nullable|string|max:100', // e.g., Mathematics Faculty
            'employee_id' => 'nullable|string|max:50',  // e.g., TCH001
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $teacher = User::create([
            'institute_id' => $instituteId,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'designation' => $request->designation,
            'employee_id' => $request->employee_id,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Teacher added successfully to your institute.',
            'data' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'phone' => $teacher->phone,
                'role' => $teacher->role,
                'designation' => $teacher->designation,
                'employee_id' => $teacher->employee_id,
                'is_active' => $teacher->is_active,
                'created_at' => $teacher->created_at,
            ],
        ], 201);
    }

    /**
     * Get single teacher details
     */
    public function show(Request $request, $id)
    {
        $instituteId = $request->user()->institute_id;

        $teacher = User::where('institute_id', $instituteId)
            ->where('role', 'teacher')
            ->where('id', $id)
            ->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found in your institute.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'phone' => $teacher->phone,
                'role' => $teacher->role,
                'designation' => $teacher->designation,
                'employee_id' => $teacher->employee_id,
                'is_active' => $teacher->is_active,
                'created_at' => $teacher->created_at,
            ],
        ]);
    }

    /**
     * Update teacher details
     */
    public function update(Request $request, $id)
    {
        $instituteId = $request->user()->institute_id;

        $teacher = User::where('institute_id', $instituteId)
            ->where('role', 'teacher')
            ->where('id', $id)
            ->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found in your institute.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users')->ignore($teacher->id)],
            'phone' => ['sometimes', 'nullable', 'string', 'max:15', Rule::unique('users')->ignore($teacher->id)],
            'password' => 'sometimes|nullable|string|min:6',
            'designation' => 'sometimes|nullable|string|max:100',
            'employee_id' => 'sometimes|nullable|string|max:50',
            'is_active' => 'sometimes|boolean',
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
        if ($request->has('email')) {
            $teacher->email = $request->email;
        }
        if ($request->has('phone')) {
            $teacher->phone = $request->phone;
        }
        if ($request->has('designation')) {
            $teacher->designation = $request->designation;
        }
        if ($request->has('employee_id')) {
            $teacher->employee_id = $request->employee_id;
        }
        if ($request->has('is_active')) {
            $teacher->is_active = (bool) $request->is_active;
        }
        if ($request->filled('password')) {
            $teacher->password = Hash::make($request->password);
        }

        $teacher->save();

        return response()->json([
            'success' => true,
            'message' => 'Teacher updated successfully.',
            'data' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'phone' => $teacher->phone,
                'role' => $teacher->role,
                'designation' => $teacher->designation,
                'employee_id' => $teacher->employee_id,
                'is_active' => $teacher->is_active,
                'updated_at' => $teacher->updated_at,
            ],
        ]);
    }

    /**
     * Toggle active/inactive status of a teacher
     */
    public function toggleStatus(Request $request, $id)
    {
        $instituteId = $request->user()->institute_id;

        $teacher = User::where('institute_id', $instituteId)
            ->where('role', 'teacher')
            ->where('id', $id)
            ->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found in your institute.',
            ], 404);
        }

        $teacher->is_active = !$teacher->is_active;
        $teacher->save();

        return response()->json([
            'success' => true,
            'message' => 'Teacher status toggled to ' . ($teacher->is_active ? 'active' : 'inactive') . '.',
            'data' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'is_active' => $teacher->is_active,
            ],
        ]);
    }

    /**
     * Remove / Delete teacher from institute
     */
    public function destroy(Request $request, $id)
    {
        $instituteId = $request->user()->institute_id;

        $teacher = User::where('institute_id', $instituteId)
            ->where('role', 'teacher')
            ->where('id', $id)
            ->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found in your institute.',
            ], 404);
        }

        $teacher->tokens()->delete(); // Revoke any API tokens
        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teacher removed successfully.',
        ]);
    }
}
