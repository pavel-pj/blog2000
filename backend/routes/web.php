<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


/*
Route::get('/sanctum/2rf-cookie', function () {
    return response()->noContent();
});
*/
