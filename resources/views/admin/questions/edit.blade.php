@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Question</h1>
    <p class="text-gray-600 mt-1">Update question details</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.tests.questions.update', [$test, $question]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            @if($test->is_test_series && $test->subjects->count() > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                <select name="subject_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('subject_id') border-red-500 @enderror">
                    <option value="">Select Subject</option>
                    @foreach($test->subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $question->subject_id) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Question *</label>
                <textarea name="question_text" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('question_text') border-red-500 @enderror">{{ old('question_text', $question->question_text) }}</textarea>
                @error('question_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Question Image (Optional)</label>
                @if($question->question_image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $question->question_image) }}" alt="Current Image" class="max-w-xs rounded border">
                        <p class="text-xs text-gray-500 mt-1">Current Image</p>
                    </div>
                @endif
                <input type="file" name="question_image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('question_image') border-red-500 @enderror"
                    onchange="previewImage(event)">
                <p class="mt-1 text-xs text-gray-500">Upload a new image to replace (JPG, PNG, GIF - Max 2MB)</p>
                @error('question_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="image_preview" class="mt-3" style="display: none;">
                    <img id="preview" src="" alt="Preview" class="max-w-xs rounded border">
                </div>
            </div>

            <script>
                function previewImage(event) {
                    const preview = document.getElementById('preview');
                    const previewDiv = document.getElementById('image_preview');
                    const file = event.target.files[0];
                    
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            previewDiv.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    }
                }
            </script>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Option A *</label>
                    <input type="text" name="option_a" value="{{ old('option_a', $question->options['a'] ?? '') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('option_a') border-red-500 @enderror">
                    @error('option_a')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Option B *</label>
                    <input type="text" name="option_b" value="{{ old('option_b', $question->options['b'] ?? '') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('option_b') border-red-500 @enderror">
                    @error('option_b')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Option C</label>
                    <input type="text" name="option_c" value="{{ old('option_c', $question->options['c'] ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('option_c') border-red-500 @enderror">
                    @error('option_c')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Option D</label>
                    <input type="text" name="option_d" value="{{ old('option_d', $question->options['d'] ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('option_d') border-red-500 @enderror">
                    @error('option_d')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer *</label>
                <select name="correct_answer" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('correct_answer') border-red-500 @enderror">
                    <option value="">Select Correct Answer</option>
                    <option value="a" {{ old('correct_answer', $question->correct_option) == 'a' ? 'selected' : '' }}>Option A</option>
                    <option value="b" {{ old('correct_answer', $question->correct_option) == 'b' ? 'selected' : '' }}>Option B</option>
                    <option value="c" {{ old('correct_answer', $question->correct_option) == 'c' ? 'selected' : '' }}>Option C</option>
                    <option value="d" {{ old('correct_answer', $question->correct_option) == 'd' ? 'selected' : '' }}>Option D</option>
                </select>
                @error('correct_answer')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Explanation</label>
                <textarea name="explanation" rows="2"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('explanation') border-red-500 @enderror">{{ old('explanation', $question->explanation) }}</textarea>
                @error('explanation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marks</label>
                    <input type="number" name="marks" value="{{ old('marks', $question->marks) }}" min="0" step="0.5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('marks') border-red-500 @enderror">
                    @error('marks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Negative Marks</label>
                    <input type="number" name="negative_marks" value="{{ old('negative_marks', $question->negative_marks) }}" min="0" step="0.25"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('negative_marks') border-red-500 @enderror">
                    @error('negative_marks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Update Question
            </button>
            <a href="{{ route('admin.tests.questions.index', $test) }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
