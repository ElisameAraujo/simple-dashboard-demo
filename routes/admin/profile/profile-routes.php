<?php

use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

$middleware = ['verified'];

Route::prefix('admin/profile')->group(function () {

    Route::get('my-profile', [ProfileController::class, 'myProfile'])
        ->name('admin.account.my-profile');

    Route::get('security', [ProfileController::class, 'security'])
        ->name('admin.account.security');

    Route::get('notifications', [ProfileController::class, 'notifications'])
        ->name('admin.account.notifications');
});
