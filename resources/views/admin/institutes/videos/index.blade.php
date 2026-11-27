@extends('admin.layouts.app')

@section('title', 'Teacher Videos')
@section('page-title', 'Teacher Videos (Institute Portal)')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Title Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Videos for Teachers</h2>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                    Institute / Teacher Only
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-0.5">Manage video lectures, teaching demos, and materials strictly for institute teachers</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.institute-videos.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">upload_file</span>
                <span>Upload Teacher Video</span>
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <!-- Table Toolbar -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
            <span class="text-xs font-medium text-slate-500">
                Total Teacher Videos: <span class="font-bold text-slate-900">{{ $videos->total() }}</span>
            </span>
            <span class="text-xs text-indigo-700 font-semibold bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                100% Free for Teachers
            </span>
        </div>

        <!-- Table Element -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Video Asset</th>
                        <th class="px-6 py-4">Course & Subject</th>
                        <th class="px-6 py-4">Playlist</th>
                        <th class="px-6 py-4">Source</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($videos as $video)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Thumbnail & Title -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                @if($video->thumbnail)
                                    <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->title }}" class="h-12 w-18 object-cover rounded-xl border border-slate-200 shadow-2xs flex-shrink-0">
                                @else
                                    <div class="h-12 w-18 rounded-xl bg-gradient-to-tr from-purple-500 to-indigo-600 flex items-center justify-center text-white flex-shrink-0 shadow-2xs">
                                        <span class="material-symbols-rounded text-xl">play_circle</span>
                                    </div>
                                @endif
                                <div class="min-w-0 max-w-sm">
                                    <p class="font-bold text-slate-900 text-sm leading-tight truncate group-hover:text-indigo-600 transition-colors">{{ $video->title }}</p>
                                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ $video->description ? Str::limit($video->description, 40) : 'No description' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Course & Subject -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-800">{{ $video->course->title ?? 'N/A' }}</span>
                                <span class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $video->subject->name ?? 'General' }}</span>
                            </div>
                        </td>

                        <!-- Playlist -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($video->playlist)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <span class="material-symbols-rounded text-sm">playlist_play</span>
                                    {{ Str::limit($video->playlist->title, 25) }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400">Direct / No Playlist</span>
                            @endif
                        </td>

                        <!-- Source Type -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider {{ $video->video_type === 'youtube' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-sky-50 text-sky-600 border border-sky-100' }}">
                                {{ $video->video_type ?? 'url' }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($video->is_active)
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
                                <a href="{{ route('admin.institute-videos.edit', $video->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>
                                <form action="{{ route('admin.institute-videos.destroy', $video->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this teacher video?')">
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
                            <span class="material-symbols-rounded text-4xl block mb-2 text-slate-300">smart_display</span>
                            <p class="text-sm font-semibold">No Teacher Videos found</p>
                            <p class="text-xs text-slate-400 mt-1">Upload video lectures and lessons exclusively for institute teachers.</p>
                            <a href="{{ route('admin.institute-videos.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                                <span class="material-symbols-rounded text-sm">upload_file</span>
                                Upload First Teacher Video
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($videos->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $videos->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
