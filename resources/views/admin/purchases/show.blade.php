@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Purchase Details</h1>
        <p class="text-gray-600 mt-1">Purchase #{{ $purchase->id }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.purchases.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
            ← Back to Purchases
        </a>
        <a href="{{ route('admin.purchases.edit', $purchase) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            Edit Purchase
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Transaction Information</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Purchase ID</p>
                    <p class="text-base font-semibold text-gray-900">#{{ $purchase->id }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <div class="mt-1">
                        @if($purchase->status === 'completed')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        @elseif($purchase->status === 'pending')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @elseif($purchase->status === 'failed')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                Failed
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst($purchase->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Amount</p>
                    <p class="text-xl font-bold text-gray-900">{{ $purchase->currency }} {{ number_format($purchase->amount, 2) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Payment Method</p>
                    <p class="text-base font-medium text-gray-900">{{ ucfirst($purchase->payment_method ?? 'N/A') }}</p>
                </div>

                @if($purchase->transaction_id)
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Transaction ID</p>
                    <p class="text-base font-mono text-gray-900">{{ $purchase->transaction_id }}</p>
                </div>
                @endif

                <div>
                    <p class="text-sm text-gray-500">Purchase Date</p>
                    <p class="text-base text-gray-900">{{ $purchase->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Last Updated</p>
                    <p class="text-base text-gray-900">{{ $purchase->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            @if($purchase->notes)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-2">Notes</p>
                <p class="text-base text-gray-900">{{ $purchase->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- User Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Student Details</h3>
            
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full flex items-center justify-center mr-3">
                    <span class="text-white font-semibold text-lg">{{ strtoupper(substr($purchase->user->name ?? 'U', 0, 1)) }}</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $purchase->user->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $purchase->user->email ?? 'N/A' }}</p>
                </div>
            </div>

            @if($purchase->user->phone)
            <div class="text-sm">
                <p class="text-gray-500">Phone</p>
                <p class="text-gray-900">{{ $purchase->user->phone }}</p>
            </div>
            @endif
        </div>

        <!-- Course Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Course Details</h3>
            
            <p class="font-semibold text-gray-900 mb-2">{{ $purchase->course->title ?? 'N/A' }}</p>
            
            @if($purchase->course->description)
            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($purchase->course->description, 100) }}</p>
            @endif

            <div class="text-sm space-y-2">
                <div>
                    <span class="text-gray-500">Course Price:</span>
                    <span class="font-semibold text-gray-900">{{ $purchase->course->currency ?? 'INR' }} {{ number_format($purchase->course->price ?? 0, 2) }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Status:</span>
                    <span class="{{ $purchase->course->is_published ? 'text-green-600' : 'text-gray-600' }}">
                        {{ $purchase->course->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
