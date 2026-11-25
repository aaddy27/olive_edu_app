<?php

namespace App\Services;

use App\Models\{Course, Test, Purchase};
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $razorpayKey;
    protected $razorpaySecret;

    public function __construct()
    {
        $this->razorpayKey = config('services.razorpay.key');
        $this->razorpaySecret = config('services.razorpay.secret');
    }

    /**
     * Create a purchase order
     */
    public function createOrder(User $user, string $purchasableType, int $purchasableId, string $paymentMethod)
    {
        // Get the purchasable item
        $purchasable = $this->getPurchasableItem($purchasableType, $purchasableId);

        if (!$purchasable) {
            throw new \Exception('Item not found');
        }

        // Check if already purchased
        if ($user->hasPurchased(get_class($purchasable), $purchasable->id)) {
            throw new \Exception('You have already purchased this item');
        }

        // Create purchase record
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'purchasable_type' => get_class($purchasable),
            'purchasable_id' => $purchasable->id,
            'amount' => $purchasable->price,
            'currency' => $purchasable->currency ?? 'INR',
            'payment_provider' => $paymentMethod,
            'payment_status' => 'pending',
        ]);

        // Create order with payment provider
        $orderData = $this->createPaymentProviderOrder($purchase, $paymentMethod);

        // Update purchase with provider data
        $purchase->update([
            'payment_meta' => $orderData,
        ]);

        return [
            'purchase' => $purchase,
            'payment_data' => $orderData,
        ];
    }

    /**
     * Create order with payment provider (Razorpay)
     */
    protected function createPaymentProviderOrder(Purchase $purchase, string $provider)
    {
        if ($provider === 'razorpay') {
            return $this->createRazorpayOrder($purchase);
        }

        // Add other providers here (PayU, Stripe, etc.)
        
        throw new \Exception('Payment provider not supported');
    }

    /**
     * Create Razorpay order
     */
    protected function createRazorpayOrder(Purchase $purchase)
    {
        // TODO: Integrate with Razorpay SDK
        // For now, returning mock data
        
        // In production:
        // $api = new \Razorpay\Api\Api($this->razorpayKey, $this->razorpaySecret);
        // $order = $api->order->create([
        //     'receipt' => $purchase->order_id,
        //     'amount' => $purchase->amount * 100, // Amount in paise
        //     'currency' => $purchase->currency,
        // ]);

        return [
            'razorpay_order_id' => 'order_' . uniqid(),
            'razorpay_key' => $this->razorpayKey,
            'amount' => $purchase->amount * 100,
            'currency' => $purchase->currency,
            'name' => config('app.name'),
            'description' => 'Purchase #' . $purchase->order_id,
        ];
    }

    /**
     * Verify and complete payment
     */
    public function verifyPayment(array $paymentData)
    {
        // Verify signature
        if (!$this->verifyRazorpaySignature($paymentData)) {
            throw new \Exception('Payment verification failed');
        }

        // Find purchase by order_id
        $purchase = Purchase::where('payment_meta->razorpay_order_id', $paymentData['razorpay_order_id'])->first();

        if (!$purchase) {
            throw new \Exception('Purchase not found');
        }

        // Update purchase status
        $purchase->update([
            'payment_status' => 'completed',
            'payment_id' => $paymentData['razorpay_payment_id'] ?? null,
        ]);

        return $purchase;
    }

    /**
     * Verify Razorpay signature
     */
    protected function verifyRazorpaySignature(array $data): bool
    {
        // TODO: Implement signature verification
        // $expectedSignature = hash_hmac('sha256', 
        //     $data['razorpay_order_id'] . '|' . $data['razorpay_payment_id'],
        //     $this->razorpaySecret
        // );
        // return hash_equals($expectedSignature, $data['razorpay_signature']);

        return true; // For development
    }

    /**
     * Get purchasable item
     */
    protected function getPurchasableItem(string $type, int $id)
    {
        return match($type) {
            'course' => Course::find($id),
            'test' => Test::find($id),
            default => null,
        };
    }

    /**
     * Handle webhook from payment provider
     */
    public function handleWebhook(string $provider, array $payload)
    {
        Log::info("Payment webhook received from {$provider}", $payload);

        if ($provider === 'razorpay') {
            return $this->handleRazorpayWebhook($payload);
        }

        throw new \Exception('Unsupported payment provider');
    }

    /**
     * Handle Razorpay webhook
     */
    protected function handleRazorpayWebhook(array $payload)
    {
        // Verify webhook signature
        // TODO: Implement webhook signature verification

        $event = $payload['event'] ?? null;

        if ($event === 'payment.captured') {
            $paymentId = $payload['payload']['payment']['entity']['id'] ?? null;
            
            // Update purchase
            $purchase = Purchase::where('payment_meta->razorpay_payment_id', $paymentId)->first();
            
            if ($purchase) {
                $purchase->update(['payment_status' => 'completed']);
            }
        }

        return ['status' => 'success'];
    }
}
