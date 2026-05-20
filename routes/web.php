<?php

use App\Helpers\RouteHelper;

/********************************************************
| Admin                                                 |
 ********************************************************/

RouteHelper::importRoutesFromFolder('admin', 'dashboard');
RouteHelper::importRoutesFromFolder('admin', 'profile');

/********************************************************
| Web                                                   |
 ********************************************************/
