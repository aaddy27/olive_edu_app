<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AuthController, 
    DashboardController, 
    ExamBoardController, 
    ExamController, 
    CourseController, 
    SubjectController,
    PlaylistController,
    VideoController,
    TestController,
    QuestionController,
    UserController,
    InstituteController,
    InstituteCourseController,
    InstituteSubjectController,
    InstitutePlaylistController,
    InstituteVideoController,
    PurchaseController
};

Route::get('/', function () {
    return view('welcome');
});

// Fallback login route for standard Laravel auth redirection
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// One-click server database seeder endpoint
Route::get('/seed-dummy-data', function () {
    if (request('secret') !== 'olive2026') {
        return response()->json(['error' => 'Unauthorized. Pass ?secret=olive2026'], 403);
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return response()->json([
            'status' => 'success',
            'message' => 'Complete dummy data seeded successfully on server!',
            'details' => explode("\n", trim(\Illuminate\Support\Facades\Artisan::output()))
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (login)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Authenticated admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Exam Boards
        Route::resource('exam-boards', ExamBoardController::class);
        
        // Exams
        Route::resource('exams', ExamController::class);
        
        // Subjects
        Route::resource('subjects', SubjectController::class);
        
        // Courses
        Route::resource('courses', CourseController::class);

        // Playlists
        Route::resource('playlists', PlaylistController::class);
        
        // Videos
        Route::resource('videos', VideoController::class);
        
        // Tests
        Route::resource('tests', TestController::class);
        Route::get('tests/{test}/child-tests', [TestController::class, 'childTests'])->name('tests.child-tests');
        
        // Questions (nested under tests)
        Route::prefix('tests/{test}')->name('tests.')->group(function () {
            Route::get('questions', [QuestionController::class, 'index'])->name('questions.index');
            Route::get('questions/create', [QuestionController::class, 'create'])->name('questions.create');
            Route::post('questions', [QuestionController::class, 'store'])->name('questions.store');
            Route::get('questions/sample-excel', [QuestionController::class, 'downloadSample'])->name('questions.sample');
            Route::post('questions/import', [QuestionController::class, 'importExcel'])->name('questions.import');
            Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
            Route::put('questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
            Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
        });
        
        // Institutes & Teacher Platform
        Route::post('institutes/{institute}/toggle-status', [InstituteController::class, 'toggleStatus'])->name('institutes.toggle-status');
        Route::resource('institutes', InstituteController::class);
        Route::resource('institute-courses', InstituteCourseController::class);
        Route::resource('institute-subjects', InstituteSubjectController::class);
        Route::resource('institute-playlists', InstitutePlaylistController::class);
        Route::resource('institute-videos', InstituteVideoController::class);

        // Users
        Route::resource('users', UserController::class);
        
        // Purchases
        Route::resource('purchases', PurchaseController::class);
    });
});
