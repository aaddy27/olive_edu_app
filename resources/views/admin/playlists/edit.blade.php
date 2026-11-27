@extends('admin.layouts.app')

@section('title', 'Edit Playlist')
@section('page-title', 'Edit Playlist')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.playlists.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">
            <span class="material-symbols-rounded text-base">arrow_back</span>
            <span>Back to Playlists</span>
        </a>
    </div>

    @if ($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs">
        <div class="font-bold mb-1">Please fix the following errors:</div>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.playlists.update', $playlist->id) }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900">Edit Playlist: {{ $playlist->title }}</h3>
            <p class="text-xs text-slate-500 mt-0.5">Update playlist settings, course or subject mapping</p>
        </div>

        <div class="space-y-5">
            <!-- Course Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Select Course <span class="text-rose-500">*</span></label>
                <select name="course_id" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $playlist->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subject Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Select Subject (Optional)</label>
                <select name="subject_id" class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    <option value="">-- All / General Subject --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $playlist->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Playlist Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $playlist->title) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">{{ old('description', $playlist->description) }}</textarea>
            </div>

            <!-- Thumbnail -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Playlist Cover / Thumbnail</label>
                @if($playlist->thumbnail_url)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ $playlist->thumbnail_url }}" alt="Cover" class="h-12 w-20 rounded-lg object-cover border border-slate-200">
                        <span class="text-xs text-slate-400">Current cover image</span>
                    </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*"
                    class="w-full px-3 py-2 bg-slate-50/50 border border-slate-200 rounded-xl text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Display Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $playlist->sort_order) }}" min="0"
                    class="w-32 px-4 py-2 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <!-- Status Toggle -->
            <div class="flex items-center gap-3 pt-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $playlist->is_active) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
                <div>
                    <span class="text-xs font-bold text-slate-800">Playlist Active Status</span>
                    <p class="text-[11px] text-slate-400">Make this playlist and its videos visible to teachers</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.playlists.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">save</span>
                <span>Update Playlist</span>
            </button>
        </div>

    </form>
</div>
@endsection
