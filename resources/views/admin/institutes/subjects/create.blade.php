@extends('admin.layouts.app')

@section('title', 'Create Teacher Subject')
@section('page-title', 'Create Teacher Subject')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.institute-subjects.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">
            <span class="material-symbols-rounded text-base">arrow_back</span>
            <span>Back to Teacher Subjects</span>
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

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 md:p-8">
        <div class="border-b border-slate-100 pb-4 mb-6">
            <div class="flex items-center gap-2">
                <h3 class="text-base font-bold text-slate-900">New Subject for Teachers</h3>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                    Institute Only
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">This subject will only be visible to teachers in the Institute App</p>
        </div>

        <form action="{{ route('admin.institute-subjects.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Exam Selection (Optional for teachers) -->
            <div>
                <label for="exam_id" class="block text-xs font-bold text-slate-700 mb-2">Exam Affiliation (Optional)</label>
                <select 
                    name="exam_id" 
                    id="exam_id"
                    class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                >
                    <option value="">General / No Specific Exam</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ old('exam_id') == $exam->id ? 'selected' : '' }}>
                            {{ $exam->examBoard->name }} - {{ $exam->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Subject Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-2">Subject Name <span class="text-rose-500">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                    placeholder="e.g., Pedagogy, Advanced Physics Teaching, Hindi Grammar"
                    required
                >
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-2">Description</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4"
                    class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                    placeholder="Brief description about this teaching subject"
                >{{ old('description') }}</textarea>
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3 pt-2">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    id="is_active" 
                    value="1"
                    {{ old('is_active', true) ? 'checked' : '' }}
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded"
                >
                <label for="is_active" class="text-xs font-medium text-slate-700">
                    Active (visible to teachers)
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.institute-subjects.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                    <span class="material-symbols-rounded text-base">save</span>
                    <span>Save Teacher Subject</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
