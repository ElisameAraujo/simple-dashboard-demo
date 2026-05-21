<?php

use App\Http\Controllers\Admin\HelpersController;
use Illuminate\Support\Facades\Route;

Route::prefix('helpers')->group(function () {
    Route::get('', [HelpersController::class, 'index'])->name('admin.helpers.index');
    Route::get('{helper}', [HelpersController::class, 'show'])->name('admin.helpers.show');
});
