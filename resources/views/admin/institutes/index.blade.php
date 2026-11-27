@extends('admin.layouts.app')

@section('title', 'Institutes')
@section('page-title', 'Institutes Management')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Institutes & Coaching Centers</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage partner institutes, institute administrators, and connected teachers</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.institutes.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                <span>Register New Institute</span>
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <!-- Table Toolbar -->
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/40">
            <form method="GET" action="{{ route('admin.institutes.index') }}" class="flex-1 flex flex-col md:flex-row items-center gap-3 max-w-2xl">
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-rounded text-xl">search</span>
                    </div>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        class="w-full pl-11 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-2xs"
                        placeholder="Search institutes by name, code, email, city..."
                    >
                </div>
                <select name="status" onchange="this.form.submit()" class="py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.institutes.index') }}" class="text-xs text-rose-500 hover:text-rose-700 font-semibold px-2">Clear</a>
                @endif
            </form>

            <div class="flex items-center gap-3">
                <span class="text-xs font-medium text-slate-500">
                    Showing <span class="font-bold text-slate-900">{{ $institutes->count() }}</span> of {{ $institutes->total() }} institutes
                </span>
            </div>
        </div>

        <!-- Material Table Element -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Institute</th>
                        <th class="px-6 py-4">Validity</th>
                        <th class="px-6 py-4">Institute Admin</th>
                        <th class="px-6 py-4 text-center">Teachers</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($institutes as $institute)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Institute Name & Logo -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($institute->logo_url)
                                    <img src="{{ $institute->logo_url }}" alt="{{ $institute->name }}" class="h-10 w-10 rounded-xl object-cover border border-slate-100 shadow-2xs">
                                @else
                                    <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-indigo-700 text-white font-bold text-sm flex items-center justify-center flex-shrink-0 shadow-xs">
                                        <span class="material-symbols-rounded text-xl">corporate_fare</span>
                                    </div>
                                @endif
                                <div class="min-w-0 max-w-sm">
                                    <a href="{{ route('admin.institutes.show', $institute->id) }}" class="font-bold text-slate-900 text-sm leading-tight truncate group-hover:text-indigo-600 transition-colors block">{{ $institute->name }}</a>
                                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ $institute->email ?? 'No email set' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Validity -->
                        <td class="px-6 py-4">
                            @if($institute->is_lifetime)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <span class="material-symbols-rounded text-sm">all_inclusive</span>
                                    <span>Lifetime</span>
                                </span>
                            @elseif($institute->isExpired())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                    <span>Expired ({{ $institute->expires_at ? $institute->expires_at->format('d M Y') : '' }})</span>
                                </span>
                            @elseif($institute->expires_at)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Till {{ $institute->expires_at->format('d M Y') }}</span>
                                </span>
                            @else
                                <span class="text-xs text-slate-400">Lifetime</span>
                            @endif
                        </td>

                        <!-- Admin Details -->
                        <td class="px-6 py-4">
                            @if($institute->admin)
                                <div class="text-xs">
                                    <p class="font-semibold text-slate-800">{{ $institute->admin->name }}</p>
                                    <p class="text-slate-400">{{ $institute->admin->email }}</p>
                                    @if($institute->admin->phone)
                                        <p class="text-slate-400">{{ $institute->admin->phone }}</p>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-100 font-medium">No admin assigned</span>
                            @endif
                        </td>

                        <!-- Teachers Count -->
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.institutes.show', $institute->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 hover:bg-indigo-100 transition">
                                <span class="material-symbols-rounded text-sm">person</span>
                                <span>{{ $institute->teachers_count }} Teachers</span>
                            </a>
                        </td>

                        <!-- Location -->
                        <td class="px-6 py-4 text-xs text-slate-600">
                            {{ $institute->city ? $institute->city . ($institute->state ? ', ' . $institute->state : '') : '—' }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.institutes.toggle-status', $institute->id) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                    onclick="return confirm('{{ $institute->is_active ? 'Deactivate this institute? Its admin and all staff will also become inactive.' : 'Activate this institute and its staff?' }}')"
                                    title="Click to toggle status"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition hover:opacity-80 {{ $institute->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $institute->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    <span>{{ $institute->is_active ? 'Active' : 'Inactive' }}</span>
                                    <span class="material-symbols-rounded text-[13px] text-slate-400">sync_alt</span>
                                </button>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.institutes.show', $institute->id) }}" title="View Institute & Teachers" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">visibility</span>
                                </a>
                                <a href="{{ route('admin.institutes.edit', $institute->id) }}" title="Edit Institute" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.institutes.destroy', $institute->id) }}" onsubmit="return confirm('Are you sure you want to delete this institute? This cannot be undone.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Institute" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <span class="material-symbols-rounded text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-symbols-rounded text-4xl text-slate-300">corporate_fare</span>
                                <h3 class="text-sm font-bold text-slate-700 mt-2">No institutes found</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Register an institute to allow teachers and staff onboarding.</p>
                                <a href="{{ route('admin.institutes.create') }}" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                                    Register Institute
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($institutes->hasPages())
        <div class="p-5 border-t border-slate-100 bg-slate-50/40">
            {{ $institutes->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
