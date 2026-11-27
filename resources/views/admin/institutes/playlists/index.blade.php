@extends('admin.layouts.app')

@section('title', 'Teacher Playlists')
@section('page-title', 'Teacher Playlists (Institute Portal)')

@section('content')
<div class="space-y-6">
    
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Playlists for Teachers</h2>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                    Institute / Teacher Only
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-0.5">Organize video lessons into structured playlists exclusively for institute teachers</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.institute-playlists.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                <span>Create Teacher Playlist</span>
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <!-- Table Toolbar -->
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/40">
            <form method="GET" action="{{ route('admin.institute-playlists.index') }}" class="flex-1 flex flex-col md:flex-row items-center gap-3 max-w-xl">
                <select name="course_id" onchange="this.form.submit()" class="w-full md:w-auto py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Filter by Teacher Course (All)</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                    @endforeach
                </select>
                @if(request('course_id'))
                    <a href="{{ route('admin.institute-playlists.index') }}" class="text-xs text-rose-500 hover:text-rose-700 font-semibold px-2">Clear Filter</a>
                @endif
            </form>

            <div class="flex items-center gap-3">
                <span class="text-xs font-medium text-slate-500">
                    Showing <span class="font-bold text-slate-900">{{ $playlists->count() }}</span> of {{ $playlists->total() }} playlists
                </span>
            </div>
        </div>

        <!-- Table Element -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Playlist</th>
                        <th class="px-6 py-4">Course & Subject</th>
                        <th class="px-6 py-4 text-center">Videos</th>
                        <th class="px-6 py-4 text-center">Order</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($playlists as $playlist)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Playlist Title -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($playlist->thumbnail_url)
                                    <img src="{{ $playlist->thumbnail_url }}" alt="{{ $playlist->title }}" class="h-10 w-14 rounded-lg object-cover border border-slate-200">
                                @else
                                    <div class="h-10 w-14 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                        <span class="material-symbols-rounded text-xl">playlist_play</span>
                                    </div>
                                @endif
                                <div class="min-w-0 max-w-sm">
                                    <p class="font-bold text-slate-900 text-sm leading-tight truncate group-hover:text-indigo-600 transition-colors">{{ $playlist->title }}</p>
                                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ $playlist->description ? Str::limit($playlist->description, 50) : 'No description' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Course & Subject -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-800 text-xs">{{ $playlist->course->title ?? 'N/A' }}</span>
                                <span class="text-[11px] text-slate-400 mt-0.5">{{ $playlist->subject->name ?? 'General / All Subjects' }}</span>
                            </div>
                        </td>

                        <!-- Video Count -->
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-violet-50 text-violet-700 border border-violet-100">
                                {{ $playlist->videos_count ?? 0 }} videos
                            </span>
                        </td>

                        <!-- Sort Order -->
                        <td class="px-6 py-4 text-center text-xs font-semibold text-slate-600">
                            {{ $playlist->sort_order }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($playlist->is_active)
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
                                <a href="{{ route('admin.institute-playlists.edit', $playlist->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>
                                <form action="{{ route('admin.institute-playlists.destroy', $playlist->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this teacher playlist?')">
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
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-rounded text-4xl block mb-2 text-slate-300">playlist_play</span>
                            <p class="text-sm font-semibold">No Teacher Playlists found</p>
                            <p class="text-xs text-slate-400 mt-1">Create playlists under teacher courses to organize lessons</p>
                            <a href="{{ route('admin.institute-playlists.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                                <span class="material-symbols-rounded text-sm">add</span>
                                Create First Teacher Playlist
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($playlists->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $playlists->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
