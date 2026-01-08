<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JokeController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::post('/logout', function (Request $request) {
        if($request->user()->tokens()->delete()){
            return response()->json([], 201);
        }
    });

    Route::get('/jokes', [JokeController::class, 'getThree']);
    Route::post('/dynamic_jokes', [JokeController::class, 'getJokes']);
});
