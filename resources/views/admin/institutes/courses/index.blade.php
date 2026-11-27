@extends('admin.layouts.app')

@section('title', 'Teacher Courses')
@section('page-title', 'Teacher Courses (Institute Portal)')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Courses for Teachers</h2>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                    Institute / Teacher Only
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-0.5">These courses and lessons are exclusively visible to institute teachers (isolated from students)</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.institute-courses.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                <span>Create Teacher Course</span>
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
            <span class="text-xs font-medium text-slate-500">
                Total Teacher Courses: <span class="font-bold text-slate-900">{{ $courses->total() }}</span>
            </span>
            <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                100% Free for Teachers
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Course</th>
                        <th class="px-6 py-4">Board</th>
                        <th class="px-6 py-4">Teacher Subjects</th>
                        <th class="px-6 py-4 text-center">Playlists</th>
                        <th class="px-6 py-4 text-center">Videos</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($courses as $course)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($course->thumbnail_url)
                                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="h-10 w-14 rounded-lg object-cover border border-slate-200 shadow-2xs">
                                @else
                                    <div class="h-10 w-14 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                        <span class="material-symbols-rounded text-xl">school</span>
                                    </div>
                                @endif
                                <div class="min-w-0 max-w-sm">
                                    <p class="font-bold text-slate-900 text-sm leading-tight truncate group-hover:text-indigo-600 transition-colors">{{ $course->title }}</p>
                                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ Str::limit($course->description, 50) }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-xs font-semibold text-slate-600">
                            {{ $course->examBoard->name ?? 'General / All Boards' }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 max-w-xs">
                                @forelse($course->subjects as $s)
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[11px] font-medium border border-slate-200/60">{{ $s->name }}</span>
                                @empty
                                    <span class="text-xs text-slate-400">No subjects linked</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $course->playlists_count }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                {{ $course->videos_count }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            @if($course->is_published)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                    Draft
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.institute-courses.edit', $course->id) }}" title="Edit Course" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.institute-courses.destroy', $course->id) }}" onsubmit="return confirm('Delete this teacher course?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Course" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
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
                                <span class="material-symbols-rounded text-4xl text-slate-300">school</span>
                                <h3 class="text-sm font-bold text-slate-700 mt-2">No teacher courses created yet</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Create courses exclusively visible to institute teachers.</p>
                                <a href="{{ route('admin.institute-courses.create') }}" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                                    Create Teacher Course
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courses->hasPages())
        <div class="p-5 border-t border-slate-100 bg-slate-50/40">
            {{ $courses->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
