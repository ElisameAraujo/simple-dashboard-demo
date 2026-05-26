<?php

return [
    'title' => 'Maintenance',
    'description' => 'Control the public availability of the site.',
    'breadcrumbs' => [
        'settings' => 'Settings',
    ],
    'status' => [
        'current' => 'Current status',
        'down' => 'In Maintenance',
        'up' => 'Site Online',
    ],
    'actions' => [
        'toggle' => 'Enable/Disable Maintenance',
        'enable' => 'Enable',
        'disable' => 'Disable',
        'cancel' => 'Cancel',
        'enable_shortcut' => 'Enable Maintenance Mode',
        'disable_shortcut' => 'Disable Maintenance Mode',
    ],
    'message' => [
        'label' => 'Maintenance Message',
        'placeholder' => 'Our system is currently under maintenance. Please come back later.',
        'default' => 'Our system is currently under maintenance. Please come back later.',
    ],
    'header_shortcut' => [
        'label' => 'Header Shortcut',
        'checkbox' => 'Show button to enable or disable maintenance',
        'description' => 'Adds a quick button to enable or disable site maintenance mode.',
    ],
    'online_alert' => [
        'label' => 'Site Online Alert',
        'checkbox' => 'Show an alert when maintenance mode is disabled',
        'duration_prefix' => 'Show alert for',
        'duration_suffix' => 'seconds',
        'description' => 'Use 0 to keep the alert always visible.',
    ],
    'modal' => [
        'enable_title' => 'Enable Maintenance Mode',
        'enable_question' => 'Are you sure you want to enable Maintenance Mode?',
        'enable_description' => 'Visitors will not be able to access the site during this period.',
        'disable_title' => 'Disable Maintenance Mode',
        'disable_question' => 'Are you sure you want to disable Maintenance Mode?',
        'disable_description' => 'Your site will become available to visitors again.',
    ],
    'flash' => [
        'updated' => 'Maintenance settings updated successfully.',
        'enabled' => 'Maintenance mode enabled successfully.',
        'disabled' => 'Maintenance mode disabled successfully.',
    ],
    'preview' => [
        'title' => 'Public preview',
        'kicker' => 'Public test route',
        'heading' => 'Site Preview',
        'description' => 'This route simulates a public page protected by the maintenance middleware.',
        'note' => 'With maintenance mode enabled, anonymous visitors receive the 503 page. Authenticated users keep seeing this page to validate changes.',
        'back' => 'Back to dashboard',
    ],
];
