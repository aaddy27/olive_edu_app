@extends('admin.layouts.app')

@section('title', 'Add Video')
@section('page-title', 'Add Video Lesson')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Top Bar with Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.videos.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-0.5">
                    <span class="material-symbols-rounded text-sm">arrow_back</span> Back to Videos
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-semibold text-slate-500">New Video</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Add Video Lesson</h2>
        </div>
    </div>

    <!-- Form Container Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-7">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                    Video Title <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title') }}" 
                    required
                    class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    placeholder="e.g., Chapter 1: Introduction to Mechanics"
                >
                @error('title')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
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
                    placeholder="Brief description of the topics covered in this lesson..."
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Course and Subject in 2 cols -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="course_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Course <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="course_id" 
                        id="course_id" 
                        required
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subject_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Subject <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="subject_id" 
                        id="subject_id" 
                        required
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->exam->name ?? 'General' }})
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Video URL ONLY (No File Upload) -->
            <div class="pt-2 border-t border-slate-100">
                <label for="video_url" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                    Video Stream URL (Vimeo / YouTube / HLS) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-rounded text-xl">link</span>
                    </div>
                    <input 
                        type="url" 
                        name="video_url" 
                        id="video_url" 
                        value="{{ old('video_url') }}" 
                        placeholder="https://vimeo.com/123456789 or https://www.youtube.com/watch?v=..."
                        required
                        class="w-full h-11 pl-11 pr-4 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                </div>
                <p class="mt-1.5 text-xs text-slate-400">
                    Paste the direct Vimeo video link, YouTube video URL, or hosted media stream URL.
                </p>
                @error('video_url')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Thumbnail & Duration -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="thumbnail" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Video Thumbnail / Poster
                    </label>
                    <input 
                        type="file" 
                        name="thumbnail" 
                        id="thumbnail" 
                        accept="image/*"
                        class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                    >
                    <p class="mt-1 text-[11px] text-slate-400">Recommended: 1280×720 (JPG, PNG, WEBP)</p>
                    @error('thumbnail')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duration" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Duration (in seconds)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-rounded text-lg">timer</span>
                        </div>
                        <input 
                            type="number" 
                            name="duration" 
                            id="duration" 
                            value="{{ old('duration') }}" 
                            min="0" 
                            placeholder="e.g. 1800 (for 30 minutes)"
                            class="w-full h-11 pl-10 pr-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                        >
                    </div>
                    @error('duration')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- View Limit & Order -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="view_limit" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Student View Limit
                    </label>
                    <input 
                        type="number" 
                        name="view_limit" 
                        id="view_limit" 
                        value="{{ old('view_limit', 0) }}" 
                        min="0"
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                    <p class="mt-1 text-[11px] text-slate-400">0 = Unlimited views allowed</p>
                    @error('view_limit')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="order" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Sort / Display Order
                    </label>
                    <input 
                        type="number" 
                        name="order" 
                        id="order" 
                        value="{{ old('order', 0) }}" 
                        min="0"
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    >
                    <p class="mt-1 text-[11px] text-slate-400">Order inside course playlist</p>
                    @error('order')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Toggles -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <label for="is_free" class="text-sm font-semibold text-slate-800 cursor-pointer">
                            Free Trial / Preview Lesson
                        </label>
                        <p class="text-xs text-slate-400">Students can watch this video without purchasing the course</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="is_free" 
                            id="is_free" 
                            value="1" 
                            {{ old('is_free') ? 'checked' : '' }}
                            class="sr-only peer"
                        >
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div>
                        <label for="is_active" class="text-sm font-semibold text-slate-800 cursor-pointer">
                            Active Status
                        </label>
                        <p class="text-xs text-slate-400">Enable or disable this video from the course syllabus</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            id="is_active" 
                            value="1" 
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="sr-only peer"
                        >
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.videos.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition border border-slate-200">
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition flex items-center gap-1.5"
                >
                    <span class="material-symbols-rounded text-base">check</span>
                    <span>Save Video Lesson</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
