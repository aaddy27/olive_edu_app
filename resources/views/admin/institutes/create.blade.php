@extends('admin.layouts.app')

@section('title', 'Register Institute')
@section('page-title', 'Register Institute')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.institutes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">
            <span class="material-symbols-rounded text-base">arrow_back</span>
            <span>Back to Institutes</span>
        </a>
    </div>

    @if ($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs">
        <div class="font-bold mb-1">Please fix the following errors:</div>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.institutes.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Institute Info Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-bold text-slate-900">Institute Profile</h3>
                <p class="text-xs text-slate-500 mt-0.5">Enter the basic details and credentials of the institute or coaching center</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Institute Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                        placeholder="e.g. Apex Academy, Global Tech Institute">
                </div>

                <!-- Logo -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Institute Logo</label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full px-3 py-2 bg-slate-50/50 border border-slate-200 rounded-xl text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Official Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                        placeholder="contact@institute.com">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Official Phone / Helpline</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                        placeholder="+91 9876543210">
                </div>

                <!-- City -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">City</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                        class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                        placeholder="e.g. Mumbai, New Delhi, Kota">
                </div>

                <!-- State -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">State</label>
                    <input type="text" name="state" value="{{ old('state') }}"
                        class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                        placeholder="e.g. Maharashtra, Rajasthan">
                </div>

                <!-- Pincode -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Pincode</label>
                    <input type="text" name="pincode" value="{{ old('pincode') }}"
                        class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                        placeholder="e.g. 400001">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Full Campus Address</label>
                    <textarea name="address" rows="2"
                        class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                        placeholder="Complete postal address...">{{ old('address') }}</textarea>
                </div>

                <!-- Subscription & Validity Section -->
                <div class="md:col-span-2 p-5 bg-indigo-50/50 border border-indigo-100 rounded-2xl space-y-3">
                    <div>
                        <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-indigo-600 text-base">verified</span>
                            Institute Validity & Expiration
                        </span>
                        <p class="text-[11px] text-slate-500 mt-0.5">Set the access validity duration or grant lifetime access</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center pt-1">
                        <!-- Lifetime Option -->
                        <div class="p-3 bg-white rounded-xl border border-slate-200 flex items-center gap-3">
                            <input type="checkbox" id="is_lifetime" name="is_lifetime" value="1" {{ old('is_lifetime', true) ? 'checked' : '' }}
                                onchange="toggleLifetimeValidity(this)"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer">
                            <label for="is_lifetime" class="text-xs font-bold text-slate-800 cursor-pointer select-none">
                                Lifetime Validity
                                <span class="block text-[11px] text-slate-400 font-normal">Never expires (Permanent institute access)</span>
                            </label>
                        </div>

                        <!-- Expiry Date -->
                        <div id="expiry_date_container" class="{{ old('is_lifetime', true) ? 'opacity-40 pointer-events-none' : '' }}">
                            <label for="expires_at" class="block text-xs font-bold text-slate-700 mb-1.5">Valid Until (Expiry Date)</label>
                            <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}"
                                class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                    </div>
                </div>

                <!-- Status Toggle -->
                <div class="md:col-span-2 flex items-center gap-3 pt-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                    <div>
                        <span class="text-xs font-bold text-slate-800">Institute Active Status</span>
                        <p class="text-[11px] text-slate-400">Allow this institute's admin and teachers to log in and access content</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Institute Administrator Account Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-indigo-600">admin_panel_settings</span>
                    <h3 class="text-base font-bold text-slate-900">Institute Admin Account</h3>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Create the master administrator login for this institute. This admin can add and manage teachers via the mobile app or API.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Admin Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Admin Full Name</label>
                    <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                        class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                        placeholder="e.g. Dr. Rajesh Sharma">
                </div>

                <!-- Admin Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Admin Login Email</label>
                    <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                        class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                        placeholder="admin@apexacademy.com">
                </div>

                <!-- Admin Phone -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Admin Mobile Number</label>
                    <input type="tel" name="admin_phone" value="{{ old('admin_phone') }}" autocomplete="off"
                        class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                        placeholder="9876543210">
                </div>

                <!-- Admin Password -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Admin Password</label>
                    <div class="relative">
                        <input type="password" id="admin_password" name="admin_password" autocomplete="new-password"
                            class="w-full pl-4 pr-11 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                            placeholder="Minimum 6 characters">
                        <button type="button" onclick="togglePasswordVisibility('admin_password', 'togglePasswordIcon')" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors"
                            title="Show/Hide Password">
                            <span id="togglePasswordIcon" class="material-symbols-rounded text-lg select-none">visibility</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.institutes.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">save</span>
                <span>Register & Create Institute</span>
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
function toggleLifetimeValidity(checkbox) {
    const container = document.getElementById('expiry_date_container');
    const input = document.getElementById('expires_at');
    if (!container || !input) return;

    if (checkbox.checked) {
        container.classList.add('opacity-40', 'pointer-events-none');
        input.value = '';
    } else {
        container.classList.remove('opacity-40', 'pointer-events-none');
    }
}

function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}
</script>
@endpush
@endsection
