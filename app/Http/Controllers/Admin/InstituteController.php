<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InstituteController extends Controller
{
    public function index(Request $request)
    {
        $query = Institute::with(['admin', 'teachers'])->withCount('teachers');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $institutes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.institutes.index', compact('institutes'));
    }

    public function create()
    {
        return view('admin.institutes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:190',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active' => 'boolean',
            'is_lifetime' => 'boolean',
            'expires_at' => 'nullable|date',
            // Optional Institute Admin Creation
            'admin_name' => 'nullable|string|max:255',
            'admin_email' => 'nullable|email|max:255|unique:users,email',
            'admin_phone' => 'nullable|string|max:15|unique:users,phone',
            'admin_password' => 'nullable|string|min:6',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('institutes/logos', 'public');
        }

        $isLifetime = $request->has('is_lifetime');
        $expiresAt = $isLifetime ? null : ($validated['expires_at'] ?? null);

        $institute = Institute::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
            'logo_path' => $logoPath,
            'is_active' => $request->has('is_active'),
            'is_lifetime' => $isLifetime,
            'expires_at' => $expiresAt,
        ]);

        // Create Institute Admin if provided
        if (!empty($validated['admin_name']) && !empty($validated['admin_email']) && !empty($validated['admin_password'])) {
            User::create([
                'institute_id' => $institute->id,
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'phone' => $validated['admin_phone'] ?? null,
                'password' => Hash::make($validated['admin_password']),
                'role' => 'institute_admin',
                'is_active' => true,
            ]);
        }

        return redirect()->route('admin.institutes.show', $institute->id)
            ->with('success', 'Institute created successfully!');
    }

    public function show(Institute $institute)
    {
        $institute->load(['admin', 'admins', 'teachers' => function ($q) {
            $q->latest();
        }]);

        return view('admin.institutes.show', compact('institute'));
    }

    public function edit(Institute $institute)
    {
        $institute->load('admin');
        return view('admin.institutes.edit', compact('institute'));
    }

    public function update(Request $request, Institute $institute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:190',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active' => 'boolean',
            'is_lifetime' => 'boolean',
            'expires_at' => 'nullable|date',
            // Update / Create Admin fields
            'admin_name' => 'nullable|string|max:255',
            'admin_email' => 'nullable|email|max:255',
            'admin_phone' => 'nullable|string|max:15',
            'admin_password' => 'nullable|string|min:6',
        ]);

        if ($request->hasFile('logo')) {
            if ($institute->logo_path) {
                Storage::disk('public')->delete($institute->logo_path);
            }
            $institute->logo_path = $request->file('logo')->store('institutes/logos', 'public');
        }

        $isLifetime = $request->has('is_lifetime');
        $expiresAt = $isLifetime ? null : ($validated['expires_at'] ?? null);

        $isActive = $request->has('is_active');

        $institute->update([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
            'is_active' => $isActive,
            'is_lifetime' => $isLifetime,
            'expires_at' => $expiresAt,
        ]);

        // When institute is deactivated, ensure all its admins and teachers are deactivated and tokens revoked
        if (!$isActive) {
            User::where('institute_id', $institute->id)->update(['is_active' => false]);
            foreach ($institute->users as $user) {
                $user->tokens()->delete();
            }
        } else {
            User::where('institute_id', $institute->id)->update(['is_active' => true]);
        }

        // Manage Institute Admin
        if (!empty($validated['admin_email'])) {
            $admin = $institute->admin;
            if ($admin) {
                $adminUpdate = [
                    'name' => $validated['admin_name'] ?? $admin->name,
                    'email' => $validated['admin_email'],
                    'phone' => $validated['admin_phone'] ?? $admin->phone,
                ];
                if (!empty($validated['admin_password'])) {
                    $adminUpdate['password'] = Hash::make($validated['admin_password']);
                }
                $admin->update($adminUpdate);
            } else {
                User::create([
                    'institute_id' => $institute->id,
                    'name' => $validated['admin_name'] ?? 'Admin',
                    'email' => $validated['admin_email'],
                    'phone' => $validated['admin_phone'] ?? null,
                    'password' => Hash::make($validated['admin_password'] ?? '123456'),
                    'role' => 'institute_admin',
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('admin.institutes.show', $institute->id)
            ->with('success', 'Institute updated successfully!');
    }

    public function toggleStatus(Institute $institute)
    {
        $institute->is_active = !$institute->is_active;
        $institute->save();

        if (!$institute->is_active) {
            User::where('institute_id', $institute->id)->update(['is_active' => false]);
            foreach ($institute->users as $user) {
                $user->tokens()->delete();
            }
            $msg = "Institute '{$institute->name}' and all its administrators & teachers have been deactivated.";
        } else {
            User::where('institute_id', $institute->id)->update(['is_active' => true]);
            $msg = "Institute '{$institute->name}' and its staff have been activated.";
        }

        return back()->with('success', $msg);
    }

    public function destroy(Institute $institute)
    {
        if ($institute->logo_path) {
            Storage::disk('public')->delete($institute->logo_path);
        }

        $institute->delete();

        return redirect()->route('admin.institutes.index')
            ->with('success', 'Institute deleted successfully!');
    }
}
