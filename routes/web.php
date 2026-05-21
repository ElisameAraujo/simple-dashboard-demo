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
RouteHelper::importRoutesFromFolder('demo', 'helpers');
RouteHelper::importRoutesFromFolder('demo', 'profile');

/********************************************************
| Web                                                   |
 ********************************************************/
