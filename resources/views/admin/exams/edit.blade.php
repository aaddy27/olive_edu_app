@extends('admin.layouts.app')

@section('title', 'Edit Exam')
@section('page-title', 'Edit Exam')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.exams.update', $exam) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Exam Board Selection -->
                <div>
                    <label for="exam_board_id" class="block text-sm font-medium text-gray-700 mb-2">Exam Board *</label>
                    <select 
                        name="exam_board_id" 
                        id="exam_board_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        required
                    >
                        <option value="">Select Exam Board</option>
                        @foreach($examBoards as $board)
                            <option value="{{ $board->id }}" {{ old('exam_board_id', $exam->exam_board_id) == $board->id ? 'selected' : '' }}>
                                {{ $board->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('exam_board_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Exam Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Exam Name *</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $exam->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >{{ old('description', $exam->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        id="is_active" 
                        value="1"
                        {{ old('is_active', $exam->is_active) ? 'checked' : '' }}
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    >
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active (visible to students)
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('admin.exams.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Update Exam
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
