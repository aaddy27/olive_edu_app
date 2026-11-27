@extends('admin.layouts.app')

@section('title', 'Edit Teacher Course')
@section('page-title', 'Edit Teacher Course')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.institute-courses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">
            <span class="material-symbols-rounded text-base">arrow_back</span>
            <span>Back to Teacher Courses</span>
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

    <form method="POST" action="{{ route('admin.institute-courses.update', $course->id) }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                    Institute / Teacher Content
                </span>
            </div>
            <h3 class="text-base font-bold text-slate-900 mt-1">Edit Course: {{ $course->title }}</h3>
            <p class="text-xs text-slate-500 mt-0.5">Manage course settings and linked teacher subjects</p>
        </div>

        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Course Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $course->title) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <!-- Exam Board (Optional) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Exam Board (Optional)</label>
                <select name="exam_board_id" class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    <option value="">-- General / Applicable to All Boards --</option>
                    @foreach($examBoards as $b)
                        <option value="{{ $b->id }}" {{ old('exam_board_id', $course->exam_board_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Teacher Subjects Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Link Teacher Subjects <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-slate-50/60 rounded-2xl border border-slate-200/80 max-h-56 overflow-y-auto">
                    @foreach($subjects as $subject)
                        <label class="flex items-center gap-2.5 p-2 bg-white rounded-xl border border-slate-200 hover:border-indigo-400 cursor-pointer transition select-none">
                            <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                {{ in_array($subject->id, old('subjects', $selectedSubjects)) ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                            <span class="text-xs font-medium text-slate-800">{{ $subject->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Course Description <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="4" required
                    class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">{{ old('description', $course->description) }}</textarea>
            </div>

            <!-- Thumbnail -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Course Cover Image</label>
                @if($course->thumbnail_url)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ $course->thumbnail_url }}" alt="Cover" class="h-12 w-20 rounded-lg object-cover border border-slate-200">
                        <span class="text-xs text-slate-400">Current cover image</span>
                    </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*"
                    class="w-full px-3 py-2 bg-slate-50/50 border border-slate-200 rounded-xl text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>

            <!-- Publish Status -->
            <div class="flex items-center gap-3 pt-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $course->is_published) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
                <div>
                    <span class="text-xs font-bold text-slate-800">Publish to Teachers</span>
                    <p class="text-[11px] text-slate-400">Published courses immediately appear on institute teachers' mobile app</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.institute-courses.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">save</span>
                <span>Update Teacher Course</span>
            </button>
        </div>

    </form>
</div>
@endsection
