<?php

use App\Http\Controllers\Api\AffirmationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\HabitController;
use App\Http\Controllers\Api\HabitLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('goals', GoalController::class);

    Route::get('habits/active', [HabitController::class, 'active']);
    Route::apiResource('habits', HabitController::class);

    Route::apiResource('affirmations', AffirmationController::class);
    Route::apiResource('habit-logs', HabitLogController::class);
});
