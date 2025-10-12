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
    Route::resource('/topics',  TopicController::class  )->only(['show','store','destroy','update']) ; ;

    Route::get('/dictionaries', [ DictionaryController::class, 'index']);
    /*
    Route::prefix('csv')->group(function () {
        Route::get('/export-users', [CsvExportController::class, 'exportUsersToCsv']);
        Route::get('/download-users', [CsvExportController::class, 'downloadUsersCsv']);
        Route::get('/list-files', [CsvExportController::class, 'listCsvFiles']);
        Route::get('/download/{filename}', [CsvExportController::class, 'downloadCsvFile']);
    });
    */
    Route::prefix('excel')->group(function () {
    // Basic exports
    Route::get('/export-users', [ExcelExportController::class, 'exportUsers']);
    Route::get('/export-words', [WordExcelExportController::class, 'exportWords']);
    //Route::get('/download-users', [ExcelExportController::class, 'downloadUsers']);
    
    // Advanced exports
    //Route::get('/export-advanced-users', [ExcelExportController::class, 'exportAdvancedUsers']);
    //Route::get('/export-multiple-sheets', [ExcelExportController::class, 'exportMultipleSheets']);
    
    // File management
    //Route::get('/list-files', [ExcelExportController::class, 'listFiles']);
    //Route::get('/download/{filename}', [ExcelExportController::class, 'downloadFile']);
 });


});

 

Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
})->middleware('web'); // Важно: middleware 'web' для сессий
