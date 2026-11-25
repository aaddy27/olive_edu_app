@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Purchase</h1>
    <p class="text-gray-600 mt-1">Update purchase details</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.purchases.update', $purchase) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Student *</label>
                <select name="user_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('user_id') border-red-500 @enderror">
                    <option value="">Select Student</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $purchase->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Course *</label>
                <select name="course_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('course_id') border-red-500 @enderror">
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $purchase->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->title }} ({{ $course->currency }} {{ number_format($course->price, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                <input type="number" name="amount" value="{{ old('amount', $purchase->amount) }}" required min="0" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('amount') border-red-500 @enderror">
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                <input type="text" name="currency" value="{{ old('currency', $purchase->currency) }}" maxlength="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('currency') border-red-500 @enderror">
                @error('currency')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <select name="payment_method"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                    <option value="">Select Payment Method</option>
                    <option value="razorpay" {{ old('payment_method', $purchase->payment_method) === 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                    <option value="paytm" {{ old('payment_method', $purchase->payment_method) === 'paytm' ? 'selected' : '' }}>Paytm</option>
                    <option value="phonepe" {{ old('payment_method', $purchase->payment_method) === 'phonepe' ? 'selected' : '' }}>PhonePe</option>
                    <option value="upi" {{ old('payment_method', $purchase->payment_method) === 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="card" {{ old('payment_method', $purchase->payment_method) === 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                    <option value="netbanking" {{ old('payment_method', $purchase->payment_method) === 'netbanking' ? 'selected' : '' }}>Net Banking</option>
                    <option value="cash" {{ old('payment_method', $purchase->payment_method) === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="other" {{ old('payment_method', $purchase->payment_method) === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                <input type="text" name="transaction_id" value="{{ old('transaction_id', $purchase->transaction_id) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('transaction_id') border-red-500 @enderror">
                @error('transaction_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('status') border-red-500 @enderror">
                    <option value="pending" {{ old('status', $purchase->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ old('status', $purchase->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ old('status', $purchase->status) === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ old('status', $purchase->status) === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $purchase->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Update Purchase
            </button>
            <a href="{{ route('admin.purchases.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
