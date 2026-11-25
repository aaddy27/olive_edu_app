<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    CourseController,
    VideoController,
    TestController,
    PurchaseController,
    TestAttemptController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Authentication Routes
    Route::prefix('auth')->group(function () {
        // Public Routes - Registration & Login
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/verify-registration-otp', [AuthController::class, 'verifyRegistrationOtp']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/login-otp', [AuthController::class, 'sendLoginOtp']);
        Route::post('/verify-login-otp', [AuthController::class, 'verifyLoginOtp']);
        
        // Forgot Password Flow
        Route::post('/forgot-password', [AuthController::class, 'sendOtp']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtpForReset']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
        
        // Protected Routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::put('/profile', [AuthController::class, 'updateProfile']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        });
    });

    // Public Routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{slug}', [CourseController::class, 'show']);
    Route::get('/courses/{id}/videos', [CourseController::class, 'videos']); // Public - shows all videos with purchase status
    
    // Test Series Routes
    Route::get('/test-series/debug', [TestController::class, 'debug']); // Debug: Check database status
    Route::get('/test-series', [TestController::class, 'index']); // All test series
    Route::get('/test-series/{id}', [TestController::class, 'show']); // Test series detail
    Route::get('/test-series/{id}/subjects', [TestController::class, 'subjects']); // Subjects in test series
    Route::get('/test-series/{testId}/subjects/{subjectId}/tests', [TestController::class, 'subjectTests']); // Tests for a subject
    
    // Old test routes (kept for backward compatibility)
    Route::get('/tests', [TestController::class, 'index']);
    Route::get('/tests/{id}', [TestController::class, 'show']);

    // Protected Routes (Require Authentication)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Course Routes
        Route::get('/my-courses', [CourseController::class, 'myCourses']);

        // Video Routes
        Route::get('/videos/{id}/stream', [VideoController::class, 'stream']);

        // Purchase Routes
        Route::post('/purchase', [PurchaseController::class, 'create']);
        Route::post('/payment/verify', [PurchaseController::class, 'verify']);
        Route::get('/my-purchases', [PurchaseController::class, 'myPurchases']);

        // Test Series - Subject Questions (Requires Purchase or Free)
        Route::get('/test-series/{testId}/subjects/{subjectId}/questions', [TestController::class, 'subjectQuestions']);
        Route::get('/tests/{testId}/questions', [TestController::class, 'testQuestions']); // Get questions for a specific test

        // Test Routes
        Route::post('/tests/{id}/start', [TestAttemptController::class, 'start']);
        Route::post('/attempts/{id}/answer', [TestAttemptController::class, 'saveAnswer']);
        Route::post('/attempts/{id}/finish', [TestAttemptController::class, 'finish']);
        Route::get('/attempts/{id}/report', [TestAttemptController::class, 'report']);
        Route::get('/my-attempts', [TestAttemptController::class, 'myAttempts']);
    });

    // Payment Webhooks (No Auth Required)
    Route::post('/payment/webhook/{provider}', [PurchaseController::class, 'webhook']);
});

