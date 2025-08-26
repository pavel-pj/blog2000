<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CatalogController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\DictionaryController;


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/catalogs',  CatalogController::class ) ;
    Route::resource('/articles',  ArticleController::class ) ;


    Route::get('/dictionaries', [ DictionaryController::class, 'index']);


});

 

Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
})->middleware('web'); // Важно: middleware 'web' для сессий
