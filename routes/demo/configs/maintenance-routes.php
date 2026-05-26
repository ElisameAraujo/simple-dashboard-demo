<?php

use App\Http\Controllers\Admin\Configs\MaintenanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('configs/maintenance')->group(function () {
    Route::get('', [MaintenanceController::class, 'index'])
        ->name('configs.maintenance');
});
