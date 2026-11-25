@extends('admin.layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Directory')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Title Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Users & Students</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage administrative personnel, instructors, and enrolled students</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">person_add</span>
                <span>Add User</span>
            </a>
        </div>
    </div>

    <!-- Enterprise Material Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <!-- Table Toolbar -->
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/40">
            
            <!-- Live Search Bar -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <span class="material-symbols-rounded text-xl">search</span>
                </div>
                <input 
                    type="text" 
                    id="userSearchInput"
                    onkeyup="window.filterDataTable('userSearchInput', 'usersDataTable', 'usersCountBadge')"
                    class="w-full pl-11 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-2xs"
                    placeholder="Search users by name, email, phone, role..."
                >
            </div>

            <!-- Toolbar Badges & Count -->
            <div class="flex items-center gap-3 self-end md:self-auto">
                <span class="text-xs font-medium text-slate-500">
                    Showing <span id="usersCountBadge" class="font-bold text-slate-900">{{ $users->count() }}</span> of {{ $users->total() }} records
                </span>
                <span class="h-4 w-px bg-slate-200"></span>
                <span class="px-2.5 py-1 text-[11px] font-semibold text-indigo-700 bg-indigo-50 rounded-lg border border-indigo-100">
                    Verified Directory
                </span>
            </div>
        </div>

        <!-- Material Table Element -->
        <div class="overflow-x-auto">
            <table id="usersDataTable" class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">User Details</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Phone Number</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 text-center">Purchases</th>
                        <th class="px-6 py-4">Account Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($users as $user)
                    <tr data-filterable="true" class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- User Name & Avatar -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr {{ $user->role === 'admin' ? 'from-purple-600 to-indigo-600' : 'from-blue-500 to-indigo-600' }} text-white font-bold text-sm flex items-center justify-center flex-shrink-0 shadow-xs">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-sm leading-tight truncate group-hover:text-indigo-600 transition-colors">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Joined {{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 text-slate-600 font-medium text-xs">
                            {{ $user->email }}
                        </td>

                        <!-- Phone -->
                        <td class="px-6 py-4 font-mono text-xs text-slate-700">
                            {{ $user->phone ?? '—' }}
                        </td>

                        <!-- Role -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200/60">
                                    <span class="material-symbols-rounded text-sm">shield_person</span>
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60">
                                    <span class="material-symbols-rounded text-sm">school</span>
                                    Student
                                </span>
                            @endif
                        </td>

                        <!-- Purchases Count -->
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $user->purchases_count }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.users.edit', $user) }}" title="Edit User" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>

                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete user {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete User" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                            <span class="material-symbols-rounded text-lg">delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr class="no-filter">
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="h-12 w-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                <span class="material-symbols-rounded text-2xl">group_off</span>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">No users found</p>
                            <p class="text-xs text-slate-400 mt-1">Get started by creating your first student or administrator.</p>
                            <a href="{{ route('admin.users.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm">
                                + Add First User
                            </a>
                        </td>
                    </tr>
                    @endforelse

                    <!-- Empty Search Filter Row -->
                    <tr id="usersDataTable-empty" style="display: none;" class="no-filter">
                        <td colspan="7" class="px-6 py-10 text-center text-xs text-slate-400">
                            <span class="material-symbols-rounded text-2xl text-slate-300 block mb-1">search_off</span>
                            No matching users found for this search filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Material Table Footer / Pagination -->
        <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">
                Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
            </span>
            <div>
                {{ $users->links() }}
            </div>
        </div>

    </div>

</div>
@endsection
