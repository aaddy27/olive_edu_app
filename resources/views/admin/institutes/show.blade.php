@extends('admin.layouts.app')

@section('title', $institute->name . ' - Institute Details')
@section('page-title', 'Institute Details')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('admin.institutes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">
            <span class="material-symbols-rounded text-base">arrow_back</span>
            <span>Back to Institutes List</span>
        </a>
        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('admin.institutes.toggle-status', $institute->id) }}" class="inline">
                @csrf
                <button type="submit" 
                    onclick="return confirm('{{ $institute->is_active ? 'Deactivate this institute? Its admin and all staff will also become inactive.' : 'Activate this institute and its staff?' }}')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 border {{ $institute->is_active ? 'bg-rose-50 hover:bg-rose-100 text-rose-700 border-rose-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border-emerald-200' }}">
                    <span class="material-symbols-rounded text-base">{{ $institute->is_active ? 'block' : 'check_circle' }}</span>
                    <span>{{ $institute->is_active ? 'Deactivate Institute' : 'Activate Institute' }}</span>
                </button>
            </form>
            <a href="{{ route('admin.institutes.edit', $institute->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <span class="material-symbols-rounded text-base">edit</span>
                <span>Edit Institute</span>
            </a>
        </div>
    </div>

    <!-- Institute Overview Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 md:p-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                @if($institute->logo_url)
                    <img src="{{ $institute->logo_url }}" alt="{{ $institute->name }}" class="h-16 w-16 rounded-2xl object-cover border border-slate-200 shadow-sm">
                @else
                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-indigo-700 text-white font-bold text-2xl flex items-center justify-center shadow-md shadow-indigo-100">
                        <span class="material-symbols-rounded text-3xl">corporate_fare</span>
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-slate-900">{{ $institute->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $institute->code }}
                        </span>
                        @if($institute->is_active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Inactive
                            </span>
                        @endif

                        @if($institute->is_lifetime)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                <span class="material-symbols-rounded text-sm">all_inclusive</span> Lifetime
                            </span>
                        @elseif($institute->isExpired())
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Expired
                            </span>
                        @elseif($institute->expires_at)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                <span class="material-symbols-rounded text-sm">event</span> Till {{ $institute->expires_at->format('M d, Y') }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 mt-1">
                        {{ $institute->address ? $institute->address . ', ' : '' }}
                        {{ $institute->city ? $institute->city . ($institute->state ? ', ' . $institute->state : '') : 'Location not specified' }}
                        {{ $institute->pincode ? ' - ' . $institute->pincode : '' }}
                    </p>
                </div>
            </div>

            <!-- Stats Badge -->
            <div class="flex items-center gap-3">
                <div class="px-4 py-3 bg-indigo-50/70 border border-indigo-100 rounded-2xl text-center">
                    <span class="text-2xl font-black text-indigo-700 block leading-tight">{{ $institute->teachers->count() }}</span>
                    <span class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider">Teachers Added</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-6 text-xs">
            <div>
                <span class="text-slate-400 block font-medium">Official Contact Email</span>
                <span class="font-bold text-slate-800 mt-0.5 block">{{ $institute->email ?? 'Not provided' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">Helpline / Phone</span>
                <span class="font-bold text-slate-800 mt-0.5 block">{{ $institute->phone ?? 'Not provided' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">Registered On</span>
                <span class="font-bold text-slate-800 mt-0.5 block">{{ $institute->created_at->format('M d, Y') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-medium">Access Validity</span>
                <span class="font-bold text-slate-800 mt-0.5 block">
                    @if($institute->is_lifetime)
                        Lifetime (Never Expires)
                    @elseif($institute->expires_at)
                        {{ $institute->expires_at->format('M d, Y') }} ({{ $institute->isExpired() ? 'Expired' : 'Active' }})
                    @else
                        Lifetime
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Institute Admin Account Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 md:p-8">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-600">admin_panel_settings</span>
                <h3 class="text-base font-bold text-slate-900">Institute Administrator (App Access)</h3>
            </div>
            <span class="text-xs text-slate-400">Can add & manage teachers via mobile app</span>
        </div>

        @if($institute->admin)
        <div class="mt-4 flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 bg-slate-50/70 rounded-2xl border border-slate-200/60">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-sm shadow-xs">
                    {{ strtoupper(substr($institute->admin->name, 0, 1)) }}
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ $institute->admin->name }}</h4>
                    <p class="text-xs text-slate-500">{{ $institute->admin->email }} &bull; {{ $institute->admin->phone ?? 'No phone' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="px-2.5 py-1 rounded-full font-semibold {{ $institute->admin->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                    {{ $institute->admin->is_active ? 'Active' : 'Disabled' }}
                </span>
                <a href="{{ route('admin.institutes.edit', $institute->id) }}" class="text-indigo-600 hover:text-indigo-800 font-bold ml-2">Edit Credentials</a>
            </div>
        </div>
        @else
        <div class="mt-4 p-4 bg-amber-50 rounded-2xl border border-amber-200 text-center">
            <p class="text-xs font-semibold text-amber-800">No administrator account is linked to this institute yet.</p>
            <a href="{{ route('admin.institutes.edit', $institute->id) }}" class="mt-2 inline-block px-4 py-1.5 bg-amber-600 text-white text-xs font-bold rounded-xl hover:bg-amber-700 transition">Create Admin Account</a>
        </div>
        @endif
    </div>

    <!-- Staff / Teachers List Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
            <div>
                <h3 class="text-base font-bold text-slate-900">Institute Teachers & Staff</h3>
                <p class="text-xs text-slate-500 mt-0.5">Staff added by the Institute Admin via Mobile App / API</p>
            </div>
            <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                {{ $institute->teachers->count() }} Teachers
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Teacher Name</th>
                        <th class="px-6 py-4">Designation / Subject</th>
                        <th class="px-6 py-4">Contact (Email & Phone)</th>
                        <th class="px-6 py-4">Staff ID</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Joined On</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($institute->teachers as $teacher)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-purple-100 text-purple-700 font-bold text-xs flex items-center justify-center">
                                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                </div>
                                <span class="font-bold text-slate-900 text-xs">{{ $teacher->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-medium">
                            {{ $teacher->designation ?? 'Teacher' }}
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <p class="text-slate-800 font-medium">{{ $teacher->email }}</p>
                            <p class="text-slate-400">{{ $teacher->phone ?? '—' }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-slate-600">
                            {{ $teacher->employee_id ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($teacher->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            {{ $teacher->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-xs text-slate-400">
                            No teachers have been added under this institute yet. The Institute Admin can add teachers using the mobile app API.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
