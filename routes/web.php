<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('properties')->name('properties.')->group(function () {
    Route::get('/',          [PropertyController::class, 'index'])->name('index');
    Route::get('/{property}',[PropertyController::class, 'show'])->name('show');
});
