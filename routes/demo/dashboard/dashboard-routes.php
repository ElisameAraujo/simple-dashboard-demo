<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

$middleware = ['verified'];

Route::prefix('')->group(function () {

    Route::get('', [DashboardController::class, 'index'])->name('dashboard');
});
