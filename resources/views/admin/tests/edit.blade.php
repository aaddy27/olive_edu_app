@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Test</h1>
    <p class="text-gray-600 mt-1">Update test details</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.tests.update', $test) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $test->title) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $test->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Parent Test Series (Optional)</label>
                <select name="parent_id" id="parent_test" onchange="toggleChildTestFields()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('parent_id') border-red-500 @enderror">
                    <option value="">None (This is a parent test series)</option>
                    @foreach($parentTests as $parentTest)
                        <option value="{{ $parentTest->id }}" {{ old('parent_id', $test->parent_id) == $parentTest->id ? 'selected' : '' }}>
                            {{ $parentTest->title }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Select if this is a child test under a test series</p>
                @error('parent_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div id="test_number_field" style="display: {{ old('parent_id', $test->parent_id) ? 'block' : 'none' }};">
                <label class="block text-sm font-medium text-gray-700 mb-2">Test Number</label>
                <input type="number" name="test_number" value="{{ old('test_number', $test->test_number) }}" min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('test_number') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Test number in the series</p>
                @error('test_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                <select name="course_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('course_id') border-red-500 @enderror">
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $test->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Subject (Optional)</label>
                <select name="subject_id" id="single_subject"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('subject_id') border-red-500 @enderror">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $test->subject_id) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center mb-4">
                    <input type="checkbox" name="is_test_series" id="is_test_series" value="1" {{ old('is_test_series', $test->is_test_series) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Test Series (Multiple Subjects)</span>
                </label>

                <div id="test_series_subjects" style="display: {{ old('is_test_series', $test->is_test_series) ? 'block' : 'none' }};" class="mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Subjects for Test Series *</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($subjects as $subject)
                            <label class="flex items-center p-2 border border-gray-200 rounded bg-white hover:bg-gray-50">
                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" 
                                    {{ in_array($subject->id, old('subjects', $test->subjects->pluck('id')->toArray())) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">{{ $subject->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('subjects')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <script>
                document.getElementById('is_test_series').addEventListener('change', function() {
                    const testSeriesDiv = document.getElementById('test_series_subjects');
                    const singleSubject = document.getElementById('single_subject');
                    if (this.checked) {
                        testSeriesDiv.style.display = 'block';
                        singleSubject.disabled = true;
                        singleSubject.value = '';
                    } else {
                        testSeriesDiv.style.display = 'none';
                        singleSubject.disabled = false;
                    }
                });

                // Trigger on page load if checked
                if (document.getElementById('is_test_series').checked) {
                    document.getElementById('single_subject').disabled = true;
                }
            </script>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                <input type="number" name="duration" value="{{ old('duration', $test->duration) }}" min="0" placeholder="60"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('duration') border-red-500 @enderror">
                @error('duration')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Marks</label>
                <input type="number" name="total_marks" value="{{ old('total_marks', $test->total_marks) }}" min="0" placeholder="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('total_marks') border-red-500 @enderror">
                @error('total_marks')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Passing Marks</label>
                <input type="number" name="passing_marks" value="{{ old('passing_marks', $test->passing_marks) }}" min="0" placeholder="40"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('passing_marks') border-red-500 @enderror">
                @error('passing_marks')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Negative Marking</label>
                <input type="number" name="negative_marking" value="{{ old('negative_marking', $test->negative_marking) }}" min="0" step="0.25" placeholder="0.25"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('negative_marking') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Marks deducted per wrong answer</p>
                @error('negative_marking')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price (₹)</label>
                <input type="number" name="price" value="{{ old('price', $test->price) }}" min="0" step="0.01" placeholder="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('price') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Set 0 for free or use 'Free Test' checkbox</p>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                <textarea name="instructions" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('instructions') border-red-500 @enderror">{{ old('instructions', $test->instructions) }}</textarea>
                @error('instructions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_free" value="1" {{ old('is_free', $test->is_free) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Free Test (available without purchase)</span>
                </label>
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $test->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>

        <script>
            function toggleChildTestFields() {
                const parentId = document.getElementById('parent_test').value;
                const testNumberField = document.getElementById('test_number_field');
                const testSeriesCheckbox = document.getElementById('is_test_series');
                const testSeriesDiv = document.getElementById('test_series_subjects');
                
                if (parentId) {
                    testNumberField.style.display = 'block';
                    if (testSeriesCheckbox) {
                        testSeriesCheckbox.checked = false;
                        testSeriesCheckbox.disabled = true;
                        if (testSeriesDiv) testSeriesDiv.style.display = 'none';
                    }
                } else {
                    testNumberField.style.display = 'none';
                    if (testSeriesCheckbox) {
                        testSeriesCheckbox.disabled = false;
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                toggleChildTestFields();
            });
        </script>

        <div class="mt-6 flex gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Update Test
            </button>
            <a href="{{ route('admin.tests.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
