@extends('admin.layouts.app')

@section('title', 'Teacher Subjects')
@section('page-title', 'Teacher Subjects (Institute Portal)')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Subjects for Teachers</h2>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                    Institute / Teacher Only
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-0.5">Academic disciplines and teaching subjects exclusively for institute faculty</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.institute-subjects.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                <span>Add Teacher Subject</span>
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <!-- Table Toolbar -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
            <span class="text-xs font-medium text-slate-500">
                Total Teacher Subjects: <span class="font-bold text-slate-900">{{ $subjects->total() }}</span>
            </span>
            <span class="text-xs text-purple-700 font-semibold bg-purple-50 px-2.5 py-1 rounded-lg border border-purple-100">
                Teacher Curriculums
            </span>
        </div>

        <!-- Table Element -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Subject Name</th>
                        <th class="px-6 py-4">Exam / Affiliation</th>
                        <th class="px-6 py-4 text-center">Videos</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Subject Name -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-purple-500 to-indigo-600 text-white font-bold text-sm flex items-center justify-center flex-shrink-0 shadow-xs">
                                    {{ substr($subject->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 max-w-sm">
                                    <p class="font-bold text-slate-900 text-sm leading-tight truncate group-hover:text-indigo-600 transition-colors">{{ $subject->name }}</p>
                                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ $subject->description ? Str::limit($subject->description, 50) : 'No description' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Exam -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($subject->exam)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    <span class="material-symbols-rounded text-sm text-slate-400">assignment</span>
                                    {{ $subject->exam->name }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-600">
                                    General Pedagogy / All
                                </span>
                            @endif
                        </td>

                        <!-- Videos Count -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-violet-50 text-violet-700 border border-violet-100">
                                {{ $subject->videos_count ?? 0 }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($subject->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.institute-subjects.edit', $subject->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>
                                <form action="{{ route('admin.institute-subjects.destroy', $subject->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this teacher subject?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                        <span class="material-symbols-rounded text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-rounded text-4xl block mb-2 text-slate-300">auto_stories</span>
                            <p class="text-sm font-semibold">No Teacher Subjects found</p>
                            <p class="text-xs text-slate-400 mt-1">Create subjects to assign videos and playlists for institute teachers.</p>
                            <a href="{{ route('admin.institute-subjects.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                                <span class="material-symbols-rounded text-sm">add</span>
                                Add First Teacher Subject
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subjects->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $subjects->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
