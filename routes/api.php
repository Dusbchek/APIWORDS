<?php

use App\Http\Controllers\WordRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordController;

Route::middleware('auth:sanctum')->post('request-word', [WordController::class, 'requestWord']);
Route::middleware('auth:sanctum')->get('word/{id}', [WordController::class, 'getWordById']);
Route::middleware('auth:sanctum')->get('words-with-options-and-category', [WordController::class, 'getWordsWithOptionsAndCategory']);
Route::middleware('auth:sanctum')->get('words', [WordController::class, 'getWords']);
Route::middleware('auth:sanctum')->get('categories', [CategoryController::class, 'getCategories']);
Route::middleware('auth:sanctum')->get('users-with-requested-words', [UserController::class, 'getUsersWithRequestedWords']);
Route::middleware('auth:sanctum')->get('/requested-words', [UserController::class, 'getAuthenticatedUserWithRequestedWords']);
Route::middleware('auth:sanctum')->post('/add-category', [CategoryController::class, 'addCategory']);
Route::middleware('auth:sanctum')->post('/random-word', [WordController::class, 'getRandomWordByCategory']);
Route::middleware('auth:sanctum')->post('/check-word-option', [WordController::class, 'checkWordOption']);
Route::middleware('auth:sanctum')->get('options', [WordController::class, 'getAllOptions']);
Route::middleware('auth:sanctum')->post('/add-word', [WordController::class, 'addNewWordWithOptions']);


Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
