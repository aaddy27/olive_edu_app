@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Overview')

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard Overview</h2>
            <p class="text-xs text-slate-500 mt-0.5">Welcome back, {{ Auth::user()->name }}! Here is a summary of your courses and students.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.courses.create') }}" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition flex items-center gap-1.5">
                <span class="material-symbols-rounded text-base">add</span>
                <span>Add Course</span>
            </a>
            <a href="{{ route('admin.tests.create') }}" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-xs font-semibold shadow-2xs transition flex items-center gap-1.5">
                <span class="material-symbols-rounded text-base">quiz</span>
                <span>Add Test</span>
            </a>
        </div>
    </div>

    <!-- Enterprise Material Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Courses Metric Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100/60 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-2xl">layers</span>
                </div>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                    <span class="material-symbols-rounded text-sm">trending_up</span>
                    Active
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Courses</p>
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">{{ number_format($total_courses) }}</p>
                <div class="mt-2 text-xs text-slate-400 flex items-center gap-1">
                    <a href="{{ route('admin.courses.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center gap-0.5">
                        Manage catalog <span class="material-symbols-rounded text-xs">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Videos Metric Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center border border-violet-100/60 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-2xl">smart_display</span>
                </div>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-violet-600 bg-violet-50 px-2 py-0.5 rounded-full border border-violet-100">
                    Media
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Video Assets</p>
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">{{ number_format($total_videos) }}</p>
                <div class="mt-2 text-xs text-slate-400 flex items-center gap-1">
                    <a href="{{ route('admin.videos.index') }}" class="text-violet-600 hover:text-violet-800 font-semibold inline-flex items-center gap-0.5">
                        View video library <span class="material-symbols-rounded text-xs">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tests Metric Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100/60 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-2xl">quiz</span>
                </div>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-100">
                    Assessments
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Test Series</p>
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">{{ number_format($total_tests) }}</p>
                <div class="mt-2 text-xs text-slate-400 flex items-center gap-1">
                    <a href="{{ route('admin.tests.index') }}" class="text-amber-600 hover:text-amber-800 font-semibold inline-flex items-center gap-0.5">
                        Assessments list <span class="material-symbols-rounded text-xs">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Users Metric Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100/60 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-rounded text-2xl">group</span>
                </div>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                    Enrolled
                </span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Enrolled Students</p>
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">{{ number_format($total_users) }}</p>
                <div class="mt-2 text-xs text-slate-400 flex items-center gap-1">
                    <a href="{{ route('admin.users.index') }}" class="text-emerald-600 hover:text-emerald-800 font-semibold inline-flex items-center gap-0.5">
                        User directory <span class="material-symbols-rounded text-xs">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Revenue & Quick Launch Bar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Revenue Spotlight Card -->
        <div class="lg:col-span-1 bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-800 rounded-3xl p-6 sm:p-8 text-white shadow-lg shadow-indigo-500/20 flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-indigo-200 uppercase tracking-wider">Financial Overview</span>
                    <span class="h-8 w-8 rounded-full bg-white/10 flex items-center justify-center text-white">
                        <span class="material-symbols-rounded text-lg">payments</span>
                    </span>
                </div>
                <div class="mt-6">
                    <p class="text-xs text-indigo-200">Total Net Revenue</p>
                    <p class="text-3xl sm:text-4xl font-extrabold tracking-tight mt-1 font-mono">₹{{ number_format($total_revenue, 2) }}</p>
                    <div class="mt-3 flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-400/20 text-emerald-200 border border-emerald-300/30">
                            {{ $total_purchases }} Completed Transactions
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-white/10 relative z-10">
                <a href="{{ route('admin.purchases.index') }}" class="w-full py-2.5 px-4 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-bold text-white transition flex items-center justify-center gap-2 border border-white/15">
                    <span>Audit All Purchases</span>
                    <span class="material-symbols-rounded text-base">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Quick Launch Pad -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Academic Quick Actions</h3>
                        <p class="text-xs text-slate-500">Fast-track entity creation & operational flows</p>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Shortcuts</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
                    <a href="{{ route('admin.exam-boards.create') }}" class="p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50/60 border border-slate-200/70 hover:border-indigo-200 transition text-center group">
                        <div class="h-10 w-10 mx-auto rounded-xl bg-white text-indigo-600 flex items-center justify-center shadow-xs border border-slate-100 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-rounded text-xl">account_balance</span>
                        </div>
                        <p class="text-xs font-bold text-slate-800 mt-2.5 group-hover:text-indigo-600">New Board</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Exam Boards</p>
                    </a>

                    <a href="{{ route('admin.exams.create') }}" class="p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50/60 border border-slate-200/70 hover:border-indigo-200 transition text-center group">
                        <div class="h-10 w-10 mx-auto rounded-xl bg-white text-blue-600 flex items-center justify-center shadow-xs border border-slate-100 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-rounded text-xl">assignment</span>
                        </div>
                        <p class="text-xs font-bold text-slate-800 mt-2.5 group-hover:text-blue-600">New Exam</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Curriculum</p>
                    </a>

                    <a href="{{ route('admin.subjects.create') }}" class="p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50/60 border border-slate-200/70 hover:border-indigo-200 transition text-center group">
                        <div class="h-10 w-10 mx-auto rounded-xl bg-white text-purple-600 flex items-center justify-center shadow-xs border border-slate-100 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-rounded text-xl">auto_stories</span>
                        </div>
                        <p class="text-xs font-bold text-slate-800 mt-2.5 group-hover:text-purple-600">New Subject</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Syllabus</p>
                    </a>

                    <a href="{{ route('admin.videos.create') }}" class="p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50/60 border border-slate-200/70 hover:border-indigo-200 transition text-center group">
                        <div class="h-10 w-10 mx-auto rounded-xl bg-white text-rose-600 flex items-center justify-center shadow-xs border border-slate-100 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-rounded text-xl">upload_file</span>
                        </div>
                        <p class="text-xs font-bold text-slate-800 mt-2.5 group-hover:text-rose-600">Add Video</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Media Asset</p>
                    </a>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="flex items-center gap-1.5 text-slate-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Portal operational & synced
                </span>
                <span class="text-slate-400">Olive Learnings Admin</span>
            </div>
        </div>

    </div>

    <!-- Miniature Enterprise Material Data Tables (Recent Data) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent Students Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Recent Enrolled Students</h3>
                    <p class="text-xs text-slate-500">Latest user signups from mobile and web</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
                    View all <span class="material-symbols-rounded text-sm">arrow_forward</span>
                </a>
            </div>

            <div class="divide-y divide-slate-100 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Student</th>
                            <th class="px-6 py-3">Phone</th>
                            <th class="px-6 py-3 text-right">Registered</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($recent_users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900 text-sm truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-600 font-mono">
                                {{ $user->phone ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-3.5 text-right text-xs text-slate-400 whitespace-nowrap">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-xs text-slate-400">
                                No student registrations logged yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Purchases Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Recent Course Transactions</h3>
                    <p class="text-xs text-slate-500">Live order captures and revenue events</p>
                </div>
                <a href="{{ route('admin.purchases.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
                    View all <span class="material-symbols-rounded text-sm">arrow_forward</span>
                </a>
            </div>

            <div class="divide-y divide-slate-100 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Course / Item</th>
                            <th class="px-6 py-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($recent_purchases as $purchase)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-3.5">
                                <p class="font-semibold text-slate-900 text-sm">{{ $purchase->user->name ?? 'Student' }}</p>
                                <span class="text-xs text-slate-400">{{ $purchase->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-600">
                                <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-medium border border-indigo-100/60">
                                    {{ class_basename($purchase->purchasable_type ?? 'Course') }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right font-mono font-bold text-emerald-600 text-sm whitespace-nowrap">
                                ₹{{ number_format($purchase->amount, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-xs text-slate-400">
                                No purchases recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
