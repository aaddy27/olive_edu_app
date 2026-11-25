@extends('admin.layouts.app')

@section('title', 'Subjects')
@section('page-title', 'Subject Curriculums')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Academic Subjects</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage subject disciplines categorized under relevant examination boards and standards</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.subjects.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                <span>Add Subject</span>
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
                    id="subjectSearchInput"
                    onkeyup="window.filterDataTable('subjectSearchInput', 'subjectsDataTable', 'subjectsCountBadge')"
                    class="w-full pl-11 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-2xs"
                    placeholder="Search subjects by name, exam, board..."
                >
            </div>

            <!-- Toolbar Badges -->
            <div class="flex items-center gap-3 self-end md:self-auto">
                <span class="text-xs font-medium text-slate-500">
                    Showing <span id="subjectsCountBadge" class="font-bold text-slate-900">{{ $subjects->count() }}</span> of {{ $subjects->total() }} subjects
                </span>
                <span class="h-4 w-px bg-slate-200"></span>
                <span class="px-2.5 py-1 text-[11px] font-semibold text-indigo-700 bg-indigo-50 rounded-lg border border-indigo-100">
                    Disciplines
                </span>
            </div>
        </div>

        <!-- Material Table Element -->
        <div class="overflow-x-auto">
            <table id="subjectsDataTable" class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Subject Name</th>
                        <th class="px-6 py-4">Exam</th>
                        <th class="px-6 py-4">Board Affiliation</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($subjects as $subject)
                    <tr data-filterable="true" class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Subject Name -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-purple-500 to-indigo-600 text-white font-bold text-sm flex items-center justify-center flex-shrink-0 shadow-xs">
                                    {{ substr($subject->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 max-w-sm">
                                    <p class="font-bold text-slate-900 text-sm leading-tight truncate group-hover:text-indigo-600 transition-colors">{{ $subject->name }}</p>
                                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ $subject->description ? Str::limit($subject->description, 40) : 'No description' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Exam -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                <span class="material-symbols-rounded text-sm text-slate-400">assignment</span>
                                {{ $subject->exam->name ?? 'N/A' }}
                            </span>
                        </td>

                        <!-- Board -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100/60">
                                <span class="material-symbols-rounded text-sm">account_balance</span>
                                {{ $subject->exam->examBoard->name ?? 'General' }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($subject->is_active)
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
                                <a href="{{ route('admin.subjects.edit', $subject) }}" title="Edit Subject" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>

                                <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete subject {{ $subject->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Subject" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
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
                                <span class="material-symbols-rounded text-2xl">auto_stories</span>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">No subjects configured</p>
                            <p class="text-xs text-slate-400 mt-1">Get started by adding subjects for your exams.</p>
                            <a href="{{ route('admin.subjects.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm">
                                + Add First Subject
                            </a>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="subjectsDataTable-empty" style="display: none;" class="no-filter">
                        <td colspan="5" class="px-6 py-10 text-center text-xs text-slate-400">
                            <span class="material-symbols-rounded text-2xl text-slate-300 block mb-1">search_off</span>
                            No matching subjects found for this search.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Material Footer / Pagination -->
        @if($subjects->hasPages())
        <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">
                Page {{ $subjects->currentPage() }} of {{ $subjects->lastPage() }}
            </span>
            <div>
                {{ $subjects->links() }}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection
