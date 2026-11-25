@extends('admin.layouts.app')

@section('title', 'Questions')
@section('page-title', 'Test Question Bank')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Title Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.tests.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-0.5">
                    <span class="material-symbols-rounded text-sm">arrow_back</span> Back to Tests
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-semibold text-slate-500">Questions</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Questions for: {{ $test->title }}</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage multiple-choice items, answer keys, solutions, and marking schema</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tests.questions.sample', $test) }}" class="px-3.5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition shadow-2xs flex items-center gap-1.5" title="Download Excel template with sample questions">
                <span class="material-symbols-rounded text-base text-slate-500">download</span>
                <span>Download Sample</span>
            </a>

            <button type="button" onclick="openExcelModal()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-emerald-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">upload_file</span>
                <span>Import Excel</span>
            </button>

            <a href="{{ route('admin.tests.questions.create', $test) }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">add_circle</span>
                <span>Add Question</span>
            </a>
        </div>
    </div>

    <!-- Enterprise Material Questions Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <!-- Table Toolbar -->
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/40">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <span class="material-symbols-rounded text-xl">search</span>
                </div>
                <input 
                    type="text" 
                    id="questionSearchInput"
                    onkeyup="window.filterDataTable('questionSearchInput', 'questionsDataTable', 'questionsCountBadge')"
                    class="w-full pl-11 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-2xs"
                    placeholder="Search question text, options, explanation..."
                >
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs font-medium text-slate-500">
                    Showing <span id="questionsCountBadge" class="font-bold text-slate-900">{{ $questions->count() }}</span> of {{ $questions->total() }} questions
                </span>
                <span class="h-4 w-px bg-slate-200"></span>
                <span class="px-2.5 py-1 text-[11px] font-semibold text-indigo-700 bg-indigo-50 rounded-lg border border-indigo-100">
                    {{ $test->total_marks ?? 0 }} Total Marks
                </span>
            </div>
        </div>

        <!-- Material Table Element -->
        <div class="overflow-x-auto">
            <table id="questionsDataTable" class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 w-16">#</th>
                        <th class="px-6 py-4">Question & Options</th>
                        <th class="px-6 py-4">Correct Key</th>
                        <th class="px-6 py-4">Marking</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($questions as $question)
                    <tr data-filterable="true" class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- Question ID -->
                        <td class="px-6 py-5 align-top">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs border border-indigo-100">
                                Q{{ $question->id }}
                            </span>
                        </td>

                        <!-- Question Text & Options -->
                        <td class="px-6 py-5 align-top">
                            <p class="font-bold text-slate-900 text-sm leading-relaxed mb-3">
                                {{ $question->question_text }}
                            </p>

                            <!-- Options Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                                @foreach($question->options as $key => $option)
                                <div class="flex items-center gap-2 p-2 rounded-xl {{ $question->correct_option === $key ? 'bg-emerald-50/80 border border-emerald-200 text-emerald-800 font-semibold' : 'bg-slate-50 border border-slate-200/60 text-slate-700' }}">
                                    <span class="h-6 w-6 rounded-lg flex items-center justify-center text-xs font-bold {{ $question->correct_option === $key ? 'bg-emerald-500 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">
                                        {{ strtoupper($key) }}
                                    </span>
                                    <span class="text-xs">{{ $option }}</span>
                                </div>
                                @endforeach
                            </div>

                            @if($question->explanation)
                                <div class="p-3 bg-blue-50/70 border border-blue-200/80 rounded-xl text-xs text-blue-900 flex items-start gap-2">
                                    <span class="material-symbols-rounded text-blue-600 text-base mt-0.5">info</span>
                                    <div>
                                        <span class="font-bold">Explanation:</span> {{ $question->explanation }}
                                    </div>
                                </div>
                            @endif
                        </td>

                        <!-- Correct Option -->
                        <td class="px-6 py-5 align-top whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="material-symbols-rounded text-sm">check_circle</span>
                                Option {{ strtoupper($question->correct_option) }}
                            </span>
                        </td>

                        <!-- Marks Schema -->
                        <td class="px-6 py-5 align-top whitespace-nowrap font-mono text-xs">
                            <div class="font-bold text-slate-800">+{{ $question->marks ?? 1 }} Mark</div>
                            @if($question->negative_marks)
                                <div class="text-[11px] text-rose-500 font-semibold mt-0.5">-{{ $question->negative_marks }} Negative</div>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-5 align-top text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.tests.questions.edit', [$test, $question]) }}" title="Edit Question" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>

                                <form action="{{ route('admin.tests.questions.destroy', [$test, $question]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Question" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <span class="material-symbols-rounded text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr class="no-filter">
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="h-12 w-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                <span class="material-symbols-rounded text-2xl">help_outline</span>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">No questions added yet</p>
                            <p class="text-xs text-slate-400 mt-1">Add multiple choice questions to configure this assessment.</p>
                            <a href="{{ route('admin.tests.questions.create', $test) }}" class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm">
                                + Add First Question
                            </a>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="questionsDataTable-empty" style="display: none;" class="no-filter">
                        <td colspan="5" class="px-6 py-10 text-center text-xs text-slate-400">
                            <span class="material-symbols-rounded text-2xl text-slate-300 block mb-1">search_off</span>
                            No matching questions found for this search.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Material Footer / Pagination -->
        @if($questions->hasPages())
        <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">
                Page {{ $questions->currentPage() }} of {{ $questions->lastPage() }}
            </span>
            <div>
                {{ $questions->links() }}
            </div>
        </div>
        @endif

    </div>

</div>

<!-- Material Excel Import Modal -->
<div id="excelImportModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" onclick="closeExcelModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
            
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                        <span class="material-symbols-rounded text-2xl">table_chart</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900" id="modal-title">Bulk Import Questions</h3>
                        <p class="text-xs text-slate-500">Upload questions for {{ $test->title }} via Excel</p>
                    </div>
                </div>
                <button type="button" onclick="closeExcelModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                    <span class="material-symbols-rounded text-xl">close</span>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.tests.questions.import', $test) }}" method="POST" enctype="multipart/form-data" id="excelUploadForm">
                @csrf
                <div class="p-6 space-y-5">
                    
                    <!-- Guidance Alert -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                        <div class="font-bold text-slate-800 flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-emerald-600 text-base">verified</span>
                            Supported Format (.xlsx, .xls, .csv)
                        </div>
                        <p class="leading-relaxed">
                            Your spreadsheet should have columns: <span class="font-semibold text-slate-800">Question Text</span>, <span class="font-semibold text-slate-800">Option A</span>, <span class="font-semibold text-slate-800">Option B</span>, <span class="font-semibold text-slate-800">Option C</span>, <span class="font-semibold text-slate-800">Option D</span>, <span class="font-semibold text-slate-800">Correct Answer</span> (A/B/C/D), <span class="font-semibold text-slate-800">Marks</span>, <span class="font-semibold text-slate-800">Negative Marks</span>, <span class="font-semibold text-slate-800">Explanation</span>.
                        </p>
                        <div class="pt-1">
                            <a href="{{ route('admin.tests.questions.sample', $test) }}" class="inline-flex items-center gap-1 text-emerald-700 hover:text-emerald-800 font-bold hover:underline">
                                <span class="material-symbols-rounded text-sm">download</span> Download Sample Template (.xlsx)
                            </a>
                        </div>
                    </div>

                    <!-- Drag & Drop / File Input Box -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Select Spreadsheet File</label>
                        <div 
                            id="dropzoneContainer"
                            class="border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-6 text-center cursor-pointer transition bg-slate-50/50 hover:bg-emerald-50/20"
                            onclick="document.getElementById('excelFileInput').click()"
                        >
                            <input 
                                type="file" 
                                name="excel_file" 
                                id="excelFileInput" 
                                accept=".xlsx,.xls,.csv" 
                                class="hidden" 
                                required
                                onchange="handleExcelFileSelect(this)"
                            >
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-12 w-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mb-2">
                                    <span class="material-symbols-rounded text-2xl">upload_file</span>
                                </div>
                                <p class="text-xs font-bold text-slate-700" id="fileChosenLabel">
                                    Click to choose or drag & drop file here
                                </p>
                                <p class="text-[11px] text-slate-400 mt-0.5" id="fileSizeLabel">
                                    Excel (.xlsx, .xls) or CSV up to 10MB
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal Actions -->
                <div class="px-6 py-4 bg-slate-50/70 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="closeExcelModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-200/50 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" id="submitExcelBtn" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-emerald-200 flex items-center gap-2">
                        <span class="material-symbols-rounded text-lg">cloud_upload</span>
                        <span>Upload & Import</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    function openExcelModal() {
        document.getElementById('excelImportModal').classList.remove('hidden');
    }

    function closeExcelModal() {
        document.getElementById('excelImportModal').classList.add('hidden');
    }

    function handleExcelFileSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
            document.getElementById('fileChosenLabel').textContent = file.name;
            document.getElementById('fileSizeLabel').textContent = `${sizeInMB} MB • Ready to import`;
            document.getElementById('dropzoneContainer').classList.add('border-emerald-500', 'bg-emerald-50/30');
        }
    }

    // Drag and drop support
    const dropzone = document.getElementById('dropzoneContainer');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-emerald-500', 'bg-emerald-50/40');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-emerald-500', 'bg-emerald-50/40');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                const fileInput = document.getElementById('excelFileInput');
                fileInput.files = files;
                handleExcelFileSelect(fileInput);
            }
        }, false);
    }

    // Form submit loading state
    const excelForm = document.getElementById('excelUploadForm');
    if (excelForm) {
        excelForm.addEventListener('submit', function() {
            const btn = document.getElementById('submitExcelBtn');
            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-rounded text-lg animate-spin">sync</span><span>Importing...</span>`;
        });
    }
</script>
@endsection
