<?php

use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

$middleware = ['verified'];

Route::prefix('profile')->group(function () {

    Route::get('my-profile', [ProfileController::class, 'myProfile'])
        ->name('account.my-profile');

    Route::get('security', [ProfileController::class, 'security'])
        ->name('account.security');

    Route::get('notifications', [ProfileController::class, 'notifications'])
        ->name('account.notifications');
});
