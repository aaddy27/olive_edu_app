<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['user', 'purchasable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $users = User::where('role', 'student')->orderBy('name')->get();
        $courses = Course::where('is_published', true)->orderBy('title')->get();

        return view('admin.purchases.create', compact('users', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:255|unique:purchases,transaction_id',
            'status' => 'required|in:pending,completed,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $validated['currency'] = $validated['currency'] ?? 'INR';

        Purchase::create($validated);

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase created successfully!');
    }

    public function edit(Purchase $purchase)
    {
        $users = User::where('role', 'student')->orderBy('name')->get();
        $courses = Course::where('is_published', true)->orderBy('title')->get();

        return view('admin.purchases.edit', compact('purchase', 'users', 'courses'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:255|unique:purchases,transaction_id,' . $purchase->id,
            'status' => 'required|in:pending,completed,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $validated['currency'] = $validated['currency'] ?? 'INR';

        $purchase->update($validated);

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase updated successfully!');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase deleted successfully!');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['user', 'course']);
        
        return view('admin.purchases.show', compact('purchase'));
    }
}
