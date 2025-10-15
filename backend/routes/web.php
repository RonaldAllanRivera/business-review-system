<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Redirect root URL to admin dashboard
Route::redirect('/', '/admin', 301)->name('home.redirect');

// Swagger UI shortcut
Route::get('/swagger', function () {
    return redirect('/api/documentation');
});
