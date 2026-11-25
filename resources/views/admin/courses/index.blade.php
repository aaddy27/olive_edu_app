@extends('admin.layouts.app')

@section('title', 'Courses Management')
@section('page-title', 'Courses Catalog')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Academic Courses</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage structured video curriculums, pricing, and assigned examination boards</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courses.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                <span>Create Course</span>
            </a>
        </div>
    </div>

    <!-- Enterprise Material Data Table Container -->
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
                    id="coursesSearchInput"
                    onkeyup="window.filterDataTable('coursesSearchInput', 'coursesDataTable', 'coursesCountBadge')"
                    class="w-full pl-11 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-2xs"
                    placeholder="Search courses by title, exam board, subjects..."
                >
            </div>

            <!-- Toolbar Badges -->
            <div class="flex items-center gap-3 self-end md:self-auto">
                <span class="text-xs font-medium text-slate-500">
                    Showing <span id="coursesCountBadge" class="font-bold text-slate-900">{{ $courses->count() }}</span> of {{ $courses->total() }} courses
                </span>
                <span class="h-4 w-px bg-slate-200"></span>
                <span class="px-2.5 py-1 text-[11px] font-semibold text-indigo-700 bg-indigo-50 rounded-lg border border-indigo-100">
                    Published Catalog
                </span>
            </div>
        </div>

        <!-- Material Table Element -->
        <div class="overflow-x-auto">
            <table id="coursesDataTable" class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Course Info</th>
                        <th class="px-6 py-4">Exam Board</th>
                        <th class="px-6 py-4">Assigned Subjects</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($courses as $course)
                    <tr data-filterable="true" class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Thumbnail & Title -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($course->thumbnail_url)
                                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="h-14 w-20 rounded-xl object-cover border border-slate-200 shadow-2xs flex-shrink-0">
                                @else
                                    <div class="h-14 w-20 rounded-xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center text-white flex-shrink-0 shadow-2xs">
                                        <span class="material-symbols-rounded text-2xl">school</span>
                                    </div>
                                @endif
                                <div class="min-w-0 max-w-sm">
                                    <p class="font-bold text-slate-900 text-sm truncate leading-snug group-hover:text-indigo-600 transition-colors">{{ $course->title }}</p>
                                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ Str::limit($course->description, 60) }}</p>
                                    <span class="inline-block mt-1 text-[11px] font-mono text-slate-400">ID: #{{ $course->id }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Exam Board -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                <span class="material-symbols-rounded text-sm text-slate-500">account_balance</span>
                                {{ $course->examBoard->name ?? 'General' }}
                            </span>
                        </td>

                        <!-- Subjects Chips -->
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5 max-w-xs">
                                @forelse($course->subjects as $subject)
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                        {{ $subject->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-400 italic">No subjects</span>
                                @endforelse
                            </div>
                        </td>

                        <!-- Price -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($course->price > 0)
                                <div class="font-mono font-bold text-slate-900 text-sm">
                                    ₹{{ number_format($course->price, 0) }}
                                </div>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    FREE
                                </span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($course->is_published)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    Draft
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.courses.edit', $course) }}" title="Edit Course" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>

                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete course {{ $course->title }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Course" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
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
                                <span class="material-symbols-rounded text-2xl">layers_clear</span>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">No courses cataloged yet</p>
                            <p class="text-xs text-slate-400 mt-1">Get started by creating your first academic course.</p>
                            <a href="{{ route('admin.courses.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm">
                                + Create New Course
                            </a>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="coursesDataTable-empty" style="display: none;" class="no-filter">
                        <td colspan="6" class="px-6 py-10 text-center text-xs text-slate-400">
                            <span class="material-symbols-rounded text-2xl text-slate-300 block mb-1">search_off</span>
                            No matching courses found for this filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Material Footer / Pagination -->
        @if($courses->hasPages())
        <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">
                Page {{ $courses->currentPage() }} of {{ $courses->lastPage() }}
            </span>
            <div>
                {{ $courses->links() }}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection
