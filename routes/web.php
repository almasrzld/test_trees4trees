<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandController;

Route::get('/', function () {
    return redirect('/lands');
});

Route::prefix('lands')->group(function () {
    Route::get('/', [LandController::class, 'index'])->name('lands.index');
});
