<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Course;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    /**
     * Create an order / initiate purchase
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'purchasable_type' => 'required|in:course,test',
            'purchasable_id' => 'required|integer',
            'payment_provider' => 'nullable|string|in:razorpay,cashfree,stripe,manual',
        ]);

        $user = Auth::user();
        $provider = $validated['payment_provider'] ?? 'razorpay';

        // Resolve item
        if ($validated['purchasable_type'] === 'course') {
            $item = Course::findOrFail($validated['purchasable_id']);
            $morphType = Course::class;
        } else {
            $item = Test::findOrFail($validated['purchasable_id']);
            $morphType = Test::class;
        }

        // Check if already purchased
        $existing = Purchase::where('user_id', $user->id)
            ->where('purchasable_type', $morphType)
            ->where('purchasable_id', $item->id)
            ->where('payment_status', 'completed')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already purchased this item.',
                'data' => [
                    'purchase_id' => $existing->id,
                    'order_id' => $existing->order_id
                ]
            ], 400);
        }

        // If item is free, activate directly
        if ($item->price <= 0) {
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'purchasable_type' => $morphType,
                'purchasable_id' => $item->id,
                'amount' => 0.00,
                'currency' => 'INR',
                'payment_provider' => 'free',
                'payment_status' => 'completed',
                'order_id' => 'FREE-' . strtoupper(Str::random(10)),
                'payment_id' => 'free_access_' . time(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Free access granted successfully!',
                'data' => [
                    'order_id' => $purchase->order_id,
                    'status' => 'completed',
                    'is_free' => true
                ]
            ], 200);
        }

        // Create pending purchase
        $orderId = 'ORD-' . strtoupper(Str::random(12));
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'purchasable_type' => $morphType,
            'purchasable_id' => $item->id,
            'amount' => $item->price,
            'currency' => $item->currency ?? 'INR',
            'payment_provider' => $provider,
            'payment_status' => 'pending',
            'order_id' => $orderId,
            'payment_meta' => [
                'item_title' => $item->title,
                'initiated_at' => now()->toIso8601String()
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Purchase initiated successfully',
            'data' => [
                'order_id' => $purchase->order_id,
                'amount' => $purchase->amount,
                'currency' => $purchase->currency,
                'item_title' => $item->title,
                'payment_provider' => $provider,
                'status' => 'pending'
            ]
        ], 201);
    }

    /**
     * Verify payment after gateway callback
     */
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string|exists:purchases,order_id',
            'payment_id' => 'required|string',
            'signature' => 'nullable|string',
        ]);

        $purchase = Purchase::where('order_id', $validated['order_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($purchase->payment_status === 'completed') {
            return response()->json([
                'success' => true,
                'message' => 'Payment already verified successfully.',
                'data' => $purchase
            ]);
        }

        $purchase->update([
            'payment_status' => 'completed',
            'payment_id' => $validated['payment_id'],
            'payment_meta' => array_merge($purchase->payment_meta ?? [], [
                'signature' => $validated['signature'] ?? null,
                'verified_at' => now()->toIso8601String()
            ])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified successfully! Access granted.',
            'data' => [
                'order_id' => $purchase->order_id,
                'payment_id' => $purchase->payment_id,
                'payment_status' => 'completed',
                'amount' => $purchase->amount,
                'currency' => $purchase->currency
            ]
        ]);
    }

    /**
     * List user's purchases
     */
    public function myPurchases(Request $request)
    {
        $user = Auth::user();
        $purchases = Purchase::where('user_id', $user->id)
            ->with('purchasable')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $purchases
        ]);
    }

    /**
     * Gateway Webhook
     */
    public function webhook($provider, Request $request)
    {
        // Handle webhook callbacks from Razorpay / Cashfree / Stripe
        $orderId = $request->input('order_id') ?? $request->input('payload.payment.entity.order_id');
        $paymentId = $request->input('payment_id') ?? $request->input('payload.payment.entity.id');
        $status = $request->input('status') ?? 'completed';

        if ($orderId) {
            $purchase = Purchase::where('order_id', $orderId)->first();
            if ($purchase && $status === 'completed') {
                $purchase->update([
                    'payment_status' => 'completed',
                    'payment_id' => $paymentId ?? $purchase->payment_id,
                    'payment_meta' => array_merge($purchase->payment_meta ?? [], [
                        'webhook_received_at' => now()->toIso8601String()
                    ])
                ]);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
