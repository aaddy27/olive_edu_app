@extends('admin.layouts.app')

@section('title', 'Edit Teacher Video')
@section('page-title', 'Edit Teacher Video')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Top Bar with Back Button -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.institute-videos.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-0.5">
                <span class="material-symbols-rounded text-sm">arrow_back</span> Back to Teacher Videos
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-xs font-semibold text-slate-500">Edit Video</span>
        </div>
    </div>

    <!-- Form Container Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <div class="p-6 sm:p-8 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Edit Video: {{ $video->title }}</h3>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                    Institute Only
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Update video lecture details, stream source, or assignment</p>
        </div>

        @if ($errors->any())
        <div class="mx-6 sm:mx-8 mt-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs">
            <div class="font-bold mb-1">Please fix the following errors:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.institute-videos.update', $video->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-7">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                    Video Title <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title', $video->title) }}" 
                    required
                    class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                >
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                    Description & Lesson Notes
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3"
                    class="w-full p-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                >{{ old('description', $video->description) }}</textarea>
            </div>

            <!-- Course, Subject and Playlist in grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="course_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Teacher Course <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="course_id" 
                        id="course_id" 
                        required
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $video->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="subject_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Teacher Subject <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="subject_id" 
                        id="subject_id" 
                        required
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $video->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="playlist_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Playlist (Optional)
                    </label>
                    <select 
                        name="playlist_id" 
                        id="playlist_id"
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                        <option value="">Select Teacher Playlist</option>
                        @foreach($playlists as $playlist)
                            <option value="{{ $playlist->id }}" {{ old('playlist_id', $video->playlist_id) == $playlist->id ? 'selected' : '' }}>
                                {{ $playlist->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Video URL & Duration in grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="video_url" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Video Stream URL <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="url" 
                        name="video_url" 
                        id="video_url" 
                        value="{{ old('video_url', $video->video_url) }}" 
                        required
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                </div>

                <div>
                    <label for="duration" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Duration (seconds)
                    </label>
                    <input 
                        type="number" 
                        name="duration" 
                        id="duration" 
                        value="{{ old('duration', $video->duration_seconds) }}" 
                        min="0"
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                </div>
            </div>

            <!-- Thumbnail & Sort Order -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Video Thumbnail
                    </label>
                    @if($video->thumbnail)
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $video->thumbnail) }}" class="h-14 w-20 object-cover rounded-xl border border-slate-200">
                            <span class="text-xs text-slate-500">Current thumbnail</span>
                        </div>
                    @endif
                    <input 
                        type="file" 
                        name="thumbnail" 
                        accept="image/*"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100"
                    >
                </div>

                <div>
                    <label for="order" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Sort Order
                    </label>
                    <input 
                        type="number" 
                        name="order" 
                        id="order" 
                        value="{{ old('order', $video->sort_order) }}" 
                        min="0"
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                </div>
            </div>

            <!-- Status Checkbox -->
            <div class="flex items-center gap-3 pt-2">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    id="is_active" 
                    value="1"
                    {{ old('is_active', $video->is_active) ? 'checked' : '' }}
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded"
                >
                <label for="is_active" class="text-xs font-medium text-slate-700">
                    Active (visible to teachers)
                </label>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.institute-videos.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                    <span class="material-symbols-rounded text-base">save</span>
                    <span>Update Teacher Video</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
