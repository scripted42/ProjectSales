<?php

use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index']);
Route::get('/car/{car:slug}', [LandingPageController::class, 'show'])->name('car.show');
