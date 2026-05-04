<?php

use App\Http\Controllers\Api\Practice\ReadAloudController;
use App\Http\Controllers\Api\V1\AttemptController;
use App\Http\Controllers\Api\V1\ProgressController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth routes (unauthenticated)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Auth routes (authenticated)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me',      [AuthController::class, 'me']);
});

/*
|--------------------------------------------------------------------------
| API v1 (authenticated)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Tasks
    Route::get('tasks',       [TaskController::class, 'index']);
    Route::get('tasks/{ulid}', [TaskController::class, 'show']);

    // Attempts
    Route::post('attempts',                      [AttemptController::class, 'store']);
    Route::put('attempts/{ulid}',                [AttemptController::class, 'update']);
    Route::post('attempts/{ulid}/audio',         [AttemptController::class, 'uploadAudio']);
    Route::post('attempts/{ulid}/submit',        [AttemptController::class, 'submit']);
    Route::get('attempts/{ulid}',                [AttemptController::class, 'show']);

    // Progress
    Route::get('progress', [ProgressController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| Practice modules (authenticated)
|--------------------------------------------------------------------------
*/
Route::prefix('practice')->middleware('auth:sanctum')->group(function () {
    Route::post('read-aloud', [ReadAloudController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Health check
|--------------------------------------------------------------------------
*/
Route::get('health', fn() => response()->json(['status' => 'ok', 'service' => 'PTE Practice API']));
