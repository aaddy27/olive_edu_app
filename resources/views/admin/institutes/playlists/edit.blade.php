@extends('admin.layouts.app')

@section('title', 'Edit Teacher Playlist')
@section('page-title', 'Edit Teacher Playlist')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.institute-playlists.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">
            <span class="material-symbols-rounded text-base">arrow_back</span>
            <span>Back to Teacher Playlists</span>
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

    <form method="POST" action="{{ route('admin.institute-playlists.update', $playlist->id) }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2">
                <h3 class="text-base font-bold text-slate-900">Edit Playlist: {{ $playlist->title }}</h3>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                    Institute Only
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Update playlist settings, teacher course or subject mapping</p>
        </div>

        <div class="space-y-5">
            <!-- Course Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Select Teacher Course <span class="text-rose-500">*</span></label>
                <select name="course_id" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $playlist->course_id) == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Subject Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Select Teacher Subject (Optional)</label>
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
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ $playlist->thumbnail_url }}" class="h-16 w-24 object-cover rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-500">Current thumbnail</span>
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

            <!-- Status -->
            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $playlist->is_active) ? 'checked' : '' }}
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                <label for="is_active" class="text-xs font-medium text-slate-700">Active (Visible to Teachers)</label>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.institute-playlists.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-base">save</span>
                <span>Update Teacher Playlist</span>
            </button>
        </div>
    </form>
</div>
@endsection
