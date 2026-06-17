<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NewsController;

Route::get('/', function () {
    return redirect()->route('news.index');
});

Route::get('/news', [NewsController::class, 'index'])
    ->name('news.index');
