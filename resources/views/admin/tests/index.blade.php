@extends('admin.layouts.app')

@section('title', 'Assessments & Tests')
@section('page-title', 'Tests & Test Series')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Title Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Assessments & Test Series</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage mock exams, section-wise practice sets, questions, marks, and durations</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tests.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                <span>Create Test</span>
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
                    id="testSearchInput"
                    onkeyup="window.filterDataTable('testSearchInput', 'testsDataTable', 'testsCountBadge')"
                    class="w-full pl-11 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-2xs"
                    placeholder="Search tests by title, course, subject..."
                >
            </div>

            <!-- Toolbar Badges -->
            <div class="flex items-center gap-3 self-end md:self-auto">
                <span class="text-xs font-medium text-slate-500">
                    Showing <span id="testsCountBadge" class="font-bold text-slate-900">{{ $tests->count() }}</span> of {{ $tests->total() }} tests
                </span>
                <span class="h-4 w-px bg-slate-200"></span>
                <span class="px-2.5 py-1 text-[11px] font-semibold text-amber-700 bg-amber-50 rounded-lg border border-amber-100">
                    Assessment Bank
                </span>
            </div>
        </div>

        <!-- Material Table Element -->
        <div class="overflow-x-auto">
            <table id="testsDataTable" class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Test Title</th>
                        <th class="px-6 py-4">Course / Subject</th>
                        <th class="px-6 py-4 text-center">Questions</th>
                        <th class="px-6 py-4">Duration & Marks</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($tests as $test)
                    <tr data-filterable="true" class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Test Title & Type -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-amber-500 to-indigo-600 text-white font-bold text-sm flex items-center justify-center flex-shrink-0 shadow-xs">
                                    <span class="material-symbols-rounded text-xl">quiz</span>
                                </div>
                                <div class="min-w-0 max-w-sm">
                                    <p class="font-bold text-slate-900 text-sm leading-tight truncate group-hover:text-indigo-600 transition-colors">{{ $test->title }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($test->is_free)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200/60">Free Access</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">Premium</span>
                                        @endif
                                        @if($test->is_test_series)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60">Test Series</span>
                                        @endif
                                        <span class="text-[11px] font-mono text-slate-400">ID: #{{ $test->id }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Course & Subject -->
                        <td class="px-6 py-4">
                            <p class="font-semibold text-slate-900 text-xs">{{ $test->course->title ?? 'General Course' }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $test->subject->name ?? 'All Subjects' }}</p>
                        </td>

                        <!-- Questions Link / Count -->
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <a href="{{ route('admin.tests.questions.index', $test) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60 hover:bg-indigo-100 transition">
                                <span class="material-symbols-rounded text-sm">help</span>
                                <span>{{ $test->questions_count }} Qs</span>
                            </a>
                        </td>

                        <!-- Duration & Marks -->
                        <td class="px-6 py-4 font-mono text-xs text-slate-700 whitespace-nowrap">
                            <div>
                                <span class="font-semibold">{{ $test->duration ? $test->duration . ' mins' : 'Untimed' }}</span>
                            </div>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                {{ $test->total_marks ?? 0 }} Marks @if($test->passing_marks)(Pass: {{ $test->passing_marks }})@endif
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($test->is_active)
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
                                @if($test->is_test_series && $test->child_tests_count > 0)
                                    <a href="{{ route('admin.tests.child-tests', $test) }}" title="View Sub-tests" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <span class="material-symbols-rounded text-lg">folder_open</span>
                                    </a>
                                @endif

                                <a href="{{ route('admin.tests.questions.index', $test) }}" title="Manage Questions" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">format_list_bulleted</span>
                                </a>

                                <a href="{{ route('admin.tests.edit', $test) }}" title="Edit Test" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>

                                <form action="{{ route('admin.tests.destroy', $test) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete test {{ $test->title }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Test" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <span class="material-symbols-rounded text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr class="no-filter">
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="h-12 w-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                <span class="material-symbols-rounded text-2xl">quiz</span>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">No assessments created</p>
                            <p class="text-xs text-slate-400 mt-1">Get started by creating your first practice test or test series.</p>
                            <a href="{{ route('admin.tests.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm">
                                + Create First Test
                            </a>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="testsDataTable-empty" style="display: none;" class="no-filter">
                        <td colspan="6" class="px-6 py-10 text-center text-xs text-slate-400">
                            <span class="material-symbols-rounded text-2xl text-slate-300 block mb-1">search_off</span>
                            No matching tests found for this search.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Material Footer / Pagination -->
        @if($tests->hasPages())
        <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">
                Page {{ $tests->currentPage() }} of {{ $tests->lastPage() }}
            </span>
            <div>
                {{ $tests->links() }}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection
