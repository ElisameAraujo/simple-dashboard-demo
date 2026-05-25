<?php

use App\Http\Controllers\Admin\ModulesController;
use Illuminate\Support\Facades\Route;

Route::prefix('modules')->group(function () {
    Route::get('', [ModulesController::class, 'index'])->name('modules.index');
    Route::get('{module}', [ModulesController::class, 'show'])->name('modules.show');
});
