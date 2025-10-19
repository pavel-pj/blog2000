<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CatalogController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\DictionaryController;
use App\Http\Controllers\API\SubjectController;
use App\Http\Controllers\API\WordController;
use App\Http\Controllers\API\TopicController; 
use App\Http\Controllers\API\ExcelExportController;
use App\Http\Controllers\API\WordExcelExportController;
use App\Http\Controllers\API\WordImportController;
use App\Http\Controllers\API\RepetitionImportController;
use App\Http\Controllers\API\RepetitionController;
use App\Http\Controllers\API\TaskController;
 
 


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');




Route::middleware(['auth:sanctum','role:Admin'])->group(function () {
    Route::resource('/catalogs',  CatalogController::class )  ;
    Route::resource('/articles',  ArticleController::class ) ;
});

Route::middleware(['auth:sanctum' ])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/subjects',  SubjectController::class ) ;
    Route::get('/subjects/{id}/words',  [WordController::class ,'index']) ;
    Route::resource('/words',  WordController::class )->only(['show','store','destroy','update']) ;
     
    Route::resource('/words',  WordController::class )->only(['show','store','destroy','update']) ;
    Route::get('/subjects/{id}/topics',  [TopicController::class ,'index']) ;
    Route::resource('/topics',  TopicController::class  )->only(['show','store','destroy','update']); 

    Route::resource('/repetitions',  RepetitionController::class );
    Route::get('/subjects/{id}/repetitions',  [RepetitionController::class, 'index'] ) ;
    Route::patch('/repetition/update-word-status',  [WordController::class,'updateStatus'] );

    Route::resource('/tasks',  TaskController::class  )->only(['update']); 


    Route::get('/dictionaries', [ DictionaryController::class, 'index']);
   










  

});


   Route::prefix('excel')->group(function () {
   
    Route::get('/{subject_id}/export-words', [WordExcelExportController::class, 'exportWords']);
   // Route::get('/{subject_id}/export-words-failed', [WordExcelExportController::class, 'exportWordsFailed']);
    
    });

    Route::post('/{subject_id}/import/words', [WordImportController::class, 'import']);

    Route::post('/{subject_id}/import/repetition-simple', [RepetitionImportController::class, 'importRepetitionSimple']);

    //Route::get('/import/words/template', [WordImportController::class, 'downloadTemplate']);


Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
})->middleware('web'); // Важно: middleware 'web' для сессий
