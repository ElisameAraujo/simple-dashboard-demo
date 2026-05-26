<?php

use App\Http\Controllers\LocaleController;
use App\Helpers\RouteHelper;
use Illuminate\Support\Facades\Route;

Route::get('locale/{locale}', [LocaleController::class, 'switch'])
    ->name('locale.switch');

/********************************************************
| Demo                                                  |
 ********************************************************/

RouteHelper::importRoutesFromFolder('demo', 'dashboard');
RouteHelper::importRoutesFromFolder('demo', 'configs');
RouteHelper::importRoutesFromFolder('demo', 'helpers');
RouteHelper::importRoutesFromFolder('demo', 'modules');
RouteHelper::importRoutesFromFolder('demo', 'profile');

/********************************************************
| Web                                                   |
 ********************************************************/
Route::middleware('site.available')->group(function () {
    Route::view('site-preview', 'web.site-preview')->name('web.preview');
});
