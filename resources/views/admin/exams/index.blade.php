@extends('admin.layouts.app')

@section('title', 'Exams')
@section('page-title', 'Examination Types')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Examinations</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage exam categories, levels, and curriculum affiliations across boards</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.exams.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                <span>Add Exam</span>
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
                    id="examSearchInput"
                    onkeyup="window.filterDataTable('examSearchInput', 'examsDataTable', 'examsCountBadge')"
                    class="w-full pl-11 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-2xs"
                    placeholder="Search exams by name, board, description..."
                >
            </div>

            <!-- Toolbar Badges -->
            <div class="flex items-center gap-3 self-end md:self-auto">
                <span class="text-xs font-medium text-slate-500">
                    Showing <span id="examsCountBadge" class="font-bold text-slate-900">{{ $exams->count() }}</span> of {{ $exams->total() }} exams
                </span>
                <span class="h-4 w-px bg-slate-200"></span>
                <span class="px-2.5 py-1 text-[11px] font-semibold text-indigo-700 bg-indigo-50 rounded-lg border border-indigo-100">
                    Curriculums
                </span>
            </div>
        </div>

        <!-- Material Table Element -->
        <div class="overflow-x-auto">
            <table id="examsDataTable" class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Exam Name</th>
                        <th class="px-6 py-4">Parent Board</th>
                        <th class="px-6 py-4 text-center">Subjects Count</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($exams as $exam)
                    <tr data-filterable="true" class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Exam Name -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-blue-500 to-indigo-600 text-white font-bold text-sm flex items-center justify-center flex-shrink-0 shadow-xs">
                                    {{ substr($exam->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 max-w-sm">
                                    <p class="font-bold text-slate-900 text-sm leading-tight truncate group-hover:text-indigo-600 transition-colors">{{ $exam->name }}</p>
                                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ $exam->description ? Str::limit($exam->description, 40) : 'No description' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Exam Board -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100/60">
                                <span class="material-symbols-rounded text-sm">account_balance</span>
                                {{ $exam->examBoard->name ?? 'General' }}
                            </span>
                        </td>

                        <!-- Subjects Count -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                <span class="material-symbols-rounded text-sm">auto_stories</span>
                                {{ $exam->subjects_count }} Subjects
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($exam->is_active)
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
                                <a href="{{ route('admin.exams.edit', $exam) }}" title="Edit Exam" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>

                                <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete exam {{ $exam->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Exam" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <span class="material-symbols-rounded text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr class="no-filter">
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="h-12 w-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                <span class="material-symbols-rounded text-2xl">assignment</span>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">No exams configured</p>
                            <p class="text-xs text-slate-400 mt-1">Get started by creating your first examination type.</p>
                            <a href="{{ route('admin.exams.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm">
                                + Add First Exam
                            </a>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="examsDataTable-empty" style="display: none;" class="no-filter">
                        <td colspan="5" class="px-6 py-10 text-center text-xs text-slate-400">
                            <span class="material-symbols-rounded text-2xl text-slate-300 block mb-1">search_off</span>
                            No matching exams found for this search.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Material Footer / Pagination -->
        @if($exams->hasPages())
        <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">
                Page {{ $exams->currentPage() }} of {{ $exams->lastPage() }}
            </span>
            <div>
                {{ $exams->links() }}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection
