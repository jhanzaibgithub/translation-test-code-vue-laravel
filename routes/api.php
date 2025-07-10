<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TranslationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 

Route::middleware('auth:api')->group(function () {

    // ✅ Create translation
    Route::post('/translations/create', [TranslationController::class, 'store']);

    // ✅ Update translation
    Route::post('/translations/update/{id}', [TranslationController::class, 'update']);

    // ✅ List or search translations
    Route::get('/translations', [TranslationController::class, 'index']);

    // ✅ Show single translation
    Route::get('/translations/{id}', [TranslationController::class, 'show']);

    Route::get('/translations/show', [TranslationController::class, 'export']);
    // ✅ Delete translation
    Route::post('/translations/delete/{id}', [TranslationController::class, 'destroy']);

    // ✅ Export translations for frontend
    Route::get('/translations-export', [TranslationController::class, 'export']);
    Route::get('/translations-show', [TranslationController::class, 'all']);

    // ✅ Get available tags
    Route::get('/tags', [TranslationController::class, 'getTags']);
});
