<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Swagger UI shortcut
Route::get('/swagger', function () {
    return redirect('/api/documentation');
});
