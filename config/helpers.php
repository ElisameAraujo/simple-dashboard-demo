<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    |
    | This is a list of custom helpers to facilitate development
    | of applications with Laravel. You can add or remove helpers as 
    | necessary. All of these helpers are available globally in the application.
    |
    */

    'global' => [
        'DateHelper'            => App\Helpers\DateHelper::class,
        'DiskHelper'            => App\Helpers\DiskHelper::class,
        'HTMLHelper'            => App\Helpers\HTMLHelper::class,
        'MediaHelper'           => App\Helpers\MediaHelper::class,
        'NotificationHelper'    => App\Helpers\NotificationHelper::class,
        'NumberHelper'          => App\Helpers\NumberHelper::class,
        'RouteHelper'           => App\Helpers\RouteHelper::class,
        'RuleHelper'            => App\Helpers\RuleHelper::class,
        'TextHelper'            => App\Helpers\TextHelper::class,
        'UserHelper'            => App\Helpers\UserHelper::class,
    ],
];
