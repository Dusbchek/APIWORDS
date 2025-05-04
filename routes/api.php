<?php

use App\Http\Controllers\WordRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::middleware('auth:sanctum')->post('request-word', [WordRequestController::class, 'requestWord']);

Route::get('word/{id}', [WordRequestController::class, 'getWordById']);

Route::get('words-with-options-and-category', [WordRequestController::class, 'getWordsWithOptionsAndCategory']);

Route::get('words', [WordRequestController::class, 'getWords']);

Route::get('categories', [WordRequestController::class, 'getCategories']);

Route::get('users-with-requested-words', [WordRequestController::class, 'getUsersWithRequestedWords']);

Route::middleware('auth:sanctum')->get('/requested-words', [WordRequestController::class, 'getAuthenticatedUserWithRequestedWords']);
Route::post('/add-category', [WordRequestController::class, 'addCategory']);

Route::middleware('auth:sanctum')->post('/random-word', [WordRequestController::class, 'getRandomWordByCategory']);

Route::middleware('auth:sanctum')->post('/check-word-option', [WordRequestController::class, 'checkWordOption']);
Route::get('options', [WordRequestController::class, 'getAllOptions']);
Route::middleware('auth:sanctum')->post('/add-word', [WordRequestController::class, 'addNewWordWithOptions']);


Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);



Route::post('register', [AuthController::class, 'register']); 
Route::post('login', [AuthController::class, 'login']);      



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


