<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserProfileController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Storage;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::post('/profile', [UserProfileController::class, 'storeOrUpdate']);

    Route::get('/profile', [UserProfileController::class, 'show']);

    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/update-email', [AuthController::class, 'updateEmail']);


    Route::post('/reports', [ReportController::class, 'store']);
    
    Route::post('/reports/{id}', [ReportController::class, 'updateReport']);

});

Route::middleware('auth:api')->post('/generate-attendance-token', [AttendanceController::class, 'generate']);
Route::post('/submit-attendance', [AttendanceController::class, 'submit']);


Route::prefix('admin')
    ->middleware(['auth:api', CheckRole::class.':admin'])
    ->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'show']);
        Route::get('/user/{id}', [DashboardController::class, 'showUserProfile']);
        Route::get('/attendance', [DashboardController::class, 'showAttendance']);
        Route::get('/attendance/{id}', [DashboardController::class, 'showAttendanceById']);
        Route::get('/reports', [DashboardController::class, 'showReports']);
    });
