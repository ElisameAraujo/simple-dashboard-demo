<?php

use App\Http\Controllers\LocaleController;
use App\Helpers\RouteHelper;

Route::get('locale/{locale}', [LocaleController::class, 'switch'])
    ->name('locale.switch');

/********************************************************
| Admin                                                 |
 ********************************************************/

RouteHelper::importRoutesFromFolder('admin', 'dashboard');
RouteHelper::importRoutesFromFolder('admin', 'profile');

/********************************************************
| Web                                                   |
 ********************************************************/
