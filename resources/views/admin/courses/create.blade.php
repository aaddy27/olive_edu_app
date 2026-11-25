@extends('admin.layouts.app')

@section('title', 'Create Course')
@section('page-title', 'Add Academic Course')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Top Bar with Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-0.5">
                    <span class="material-symbols-rounded text-sm">arrow_back</span> Back to Courses
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-semibold text-slate-500">New Course</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create Course</h2>
        </div>
    </div>

    <!-- Form Container Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-7">
            @csrf

            <!-- Exam Board Selection -->
            <div>
                <label for="exam_board_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                    Exam Board <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select 
                        name="exam_board_id" 
                        id="exam_board_id"
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                        required
                    >
                        <option value="">Select Board Affiliation</option>
                        @foreach($examBoards as $board)
                            <option value="{{ $board->id }}" {{ old('exam_board_id') == $board->id ? 'selected' : '' }}>
                                {{ $board->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('exam_board_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subjects Selection (Multiple Checkboxes) -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Assigned Subjects <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-xs text-slate-400">Select one or more subjects</span>
                </div>

                <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/50 max-h-56 overflow-y-auto space-y-2">
                    @forelse($subjects as $subject)
                        <label class="flex items-center p-2 rounded-xl hover:bg-white transition cursor-pointer select-none border border-transparent hover:border-slate-200">
                            <input 
                                type="checkbox" 
                                name="subjects[]" 
                                value="{{ $subject->id }}"
                                {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded"
                            >
                            <span class="ml-3 text-sm font-medium text-slate-800">
                                {{ $subject->name }}
                            </span>
                            <span class="ml-2 text-xs text-slate-400">
                                ({{ $subject->exam->name ?? 'Exam' }})
                            </span>
                        </label>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">No subjects available. Please create subjects first.</p>
                    @endforelse
                </div>
                @error('subjects')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                    Course Title <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title') }}"
                    class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    placeholder="e.g., Complete Mathematics for Class 12 Board Exams"
                    required
                >
                @error('title')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                    Description & Syllabus <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4"
                    class="w-full p-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                    placeholder="Provide an overview of the curriculum, learning outcomes, target audience..."
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price and Currency -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Price (0 for Free) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm font-semibold">
                            ₹
                        </div>
                        <input 
                            type="number" 
                            name="price" 
                            id="price" 
                            value="{{ old('price', 0) }}"
                            step="0.01"
                            min="0"
                            class="w-full h-11 pl-8 pr-3.5 bg-white border border-slate-300 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                            placeholder="0.00"
                            required
                        >
                    </div>
                    @error('price')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="currency" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                        Currency <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="currency" 
                        id="currency"
                        class="w-full h-11 px-3.5 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                        required
                    >
                        <option value="INR" {{ old('currency', 'INR') == 'INR' ? 'selected' : '' }}>INR (₹) - Indian Rupee</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD ($) - US Dollar</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- THUMBNAIL UPLOAD (File Upload + Live Preview) -->
            <div class="pt-2 border-t border-slate-100">
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                    Course Thumbnail Image
                </label>

                <!-- Upload Drag-and-Drop Area -->
                <div class="space-y-4">
                    
                    <!-- File Dropzone Box -->
                    <div 
                        id="dropzoneBox"
                        onclick="document.getElementById('thumbnail_file_input').click()"
                        class="border-2 border-dashed border-slate-300 hover:border-indigo-500 bg-slate-50/60 hover:bg-indigo-50/30 rounded-2xl p-6 text-center cursor-pointer transition-all group"
                    >
                        <input 
                            type="file" 
                            name="thumbnail" 
                            id="thumbnail_file_input" 
                            accept="image/png,image/jpeg,image/jpg,image/webp" 
                            class="hidden"
                            onchange="handleThumbnailPreview(this)"
                        >
                        
                        <div class="h-12 w-12 mx-auto rounded-xl bg-white text-indigo-600 flex items-center justify-center shadow-xs border border-slate-200 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-rounded text-2xl">add_photo_alternate</span>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-slate-800">
                            Click to upload thumbnail <span class="text-slate-400 font-normal">or drag & drop</span>
                        </p>
                        <p class="text-xs text-slate-400 mt-1">
                            PNG, JPG, JPEG, or WEBP (Recommended: 1280×720, max 5MB)
                        </p>
                    </div>

                    <!-- Live Image Preview Card -->
                    <div id="previewCard" class="hidden p-4 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <img id="previewImage" src="" alt="Thumbnail Preview" class="h-16 w-24 object-cover rounded-xl border border-slate-300 shadow-2xs flex-shrink-0">
                            <div class="min-w-0">
                                <p id="previewFileName" class="text-xs font-semibold text-slate-800 truncate"></p>
                                <p id="previewFileSize" class="text-[11px] text-slate-400"></p>
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md mt-1 border border-emerald-200">
                                    <span class="material-symbols-rounded text-xs">check</span> Ready to upload
                                </span>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            onclick="removeThumbnailFile()" 
                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                            title="Remove chosen image"
                        >
                            <span class="material-symbols-rounded text-xl">delete</span>
                        </button>
                    </div>

                    @error('thumbnail')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror

                    <!-- Collapsible External URL Alternative -->
                    <div class="pt-2">
                        <details class="text-xs text-slate-500 group">
                            <summary class="cursor-pointer hover:text-indigo-600 font-medium inline-flex items-center gap-1 select-none">
                                <span class="material-symbols-rounded text-sm">link</span>
                                <span>Or provide an external Image URL instead</span>
                            </summary>
                            <div class="mt-3 pl-4 border-l-2 border-indigo-200">
                                <input 
                                    type="url" 
                                    name="thumbnail_path" 
                                    id="thumbnail_path" 
                                    value="{{ old('thumbnail_path') }}"
                                    class="w-full h-10 px-3 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                    placeholder="https://example.com/course-banner.jpg"
                                >
                                <p class="text-[11px] text-slate-400 mt-1">If a file is uploaded above, it will be preferred.</p>
                            </div>
                        </details>
                    </div>

                </div>
            </div>

            <!-- Published Status Toggle -->
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <label for="is_published" class="text-sm font-semibold text-slate-800 cursor-pointer">
                        Publish Course Immediately
                    </label>
                    <p class="text-xs text-slate-400">Make this course visible to students on mobile app and web catalog</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="is_published" 
                        id="is_published" 
                        value="1"
                        {{ old('is_published', true) ? 'checked' : '' }}
                        class="sr-only peer"
                    >
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.courses.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition border border-slate-200">
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition flex items-center gap-1.5"
                >
                    <span class="material-symbols-rounded text-base">check</span>
                    <span>Save & Create Course</span>
                </button>
            </div>

        </form>
    </div>

</div>

<!-- Instant Image Preview Script -->
<script>
    function handleThumbnailPreview(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('previewFileName').textContent = file.name;
                document.getElementById('previewFileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
                document.getElementById('previewCard').classList.remove('hidden');
            };

            reader.readAsDataURL(file);
        }
    }

    function removeThumbnailFile() {
        const input = document.getElementById('thumbnail_file_input');
        input.value = '';
        document.getElementById('previewImage').src = '';
        document.getElementById('previewCard').classList.add('hidden');
    }
</script>
@endsection
