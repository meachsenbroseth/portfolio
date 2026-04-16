<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Public read-only routes
Route::get('/education', [EducationController::class, 'index']);

Route::get('/experience', [ExperienceController::class, 'index']);

Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{slug}', [ProjectController::class, 'show']);

// Protected routes (admin only)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/resetpassword', [AuthController::class, 'resetPassword']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Education
    Route::post('/education', [EducationController::class, 'store']);
    Route::put('/education/{id}', [EducationController::class, 'update']);
    Route::delete('/education/{id}', [EducationController::class, 'destroy']);

    // Experience
    Route::post('/experience', [ExperienceController::class, 'store']);
    Route::put('/experience/{id}', [ExperienceController::class, 'update']);
    Route::delete('/experience/{id}', [ExperienceController::class, 'destroy']);

    // Blogs
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);

    // Projects
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

});
