@extends('admin.layouts.app')

@section('title', 'Test Series Breakdown')
@section('page-title', $test->title)

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Title Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.tests.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-0.5">
                    <span class="material-symbols-rounded text-sm">arrow_back</span> Back to Tests
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-semibold text-slate-500">Test Series</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $test->title }}</h2>
            <p class="text-sm text-slate-500 mt-0.5">Subject-wise tests and assessment modules grouped under this series</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tests.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                <span>Add Sub-Test</span>
            </a>
        </div>
    </div>

    <!-- Subject Summary Cards -->
    @if($test->subjects && $test->subjects->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($test->subjects as $subject)
            @php
                $subjectTestCount = $childTests->where('subject_id', $subject->id)->count();
            @endphp
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $subject->name }}</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $subjectTestCount }} <span class="text-xs font-medium text-slate-500">Tests</span></p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-xl">auto_stories</span>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Enterprise Material Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <!-- Table Toolbar -->
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/40">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <span class="material-symbols-rounded text-xl">search</span>
                </div>
                <input 
                    type="text" 
                    id="childTestSearchInput"
                    onkeyup="window.filterDataTable('childTestSearchInput', 'childTestsDataTable', 'childTestsCountBadge')"
                    class="w-full pl-11 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-2xs"
                    placeholder="Search sub-tests by title, subject..."
                >
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs font-medium text-slate-500">
                    Showing <span id="childTestsCountBadge" class="font-bold text-slate-900">{{ $childTests->count() }}</span> tests
                </span>
            </div>
        </div>

        <!-- Material Table Element -->
        <div class="overflow-x-auto">
            <table id="childTestsDataTable" class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Test Title</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4 text-center">Questions</th>
                        <th class="px-6 py-4">Duration & Marks</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($childTests as $childTest)
                    <tr data-filterable="true" class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs border border-indigo-100">
                                    #{{ $childTest->id }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 text-sm leading-tight">{{ $childTest->title }}</p>
                                    @if($childTest->is_free)
                                        <span class="text-[10px] text-emerald-600 font-bold uppercase">Free</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                {{ $childTest->subject->name ?? 'All Subjects' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.tests.questions.index', $childTest) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition border border-indigo-200/60">
                                <span class="material-symbols-rounded text-sm">help</span>
                                {{ $childTest->questions_count }} Qs
                            </a>
                        </td>

                        <td class="px-6 py-4 font-mono text-xs text-slate-600">
                            <div>{{ $childTest->duration ? $childTest->duration . ' mins' : 'Untimed' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $childTest->total_marks ?? 0 }} Marks</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($childTest->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.tests.questions.index', $childTest) }}" title="Questions" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">format_list_bulleted</span>
                                </a>
                                <a href="{{ route('admin.tests.edit', $childTest) }}" title="Edit" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>
                                <form action="{{ route('admin.tests.destroy', $childTest) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <span class="material-symbols-rounded text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr class="no-filter">
                        <td colspan="6" class="px-6 py-10 text-center text-xs text-slate-400">
                            No child tests linked to this test series yet.
                        </td>
                    </tr>
                    @endforelse

                    <tr id="childTestsDataTable-empty" style="display: none;" class="no-filter">
                        <td colspan="6" class="px-6 py-10 text-center text-xs text-slate-400">
                            No matching tests found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
