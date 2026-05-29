<?php

return [
    'spotlight' => [
        'placeholder' => 'Search the dashboard...',
        'close' => 'Close search',
        'suggestions' => 'Suggestions',
        'results' => 'Results',
        'group_filters' => 'Search group filters',
        'all_groups' => 'All',
        'minimum_chars' => 'Type at least :count characters.',
        'count' => '{0} No items|{1} :count item|[2,*] :count items',
        'empty' => 'No results found.',
    ],
    'groups' => [
        'posts' => 'Posts',
        'products' => 'Products',
    ],
    'badges' => [
        'post' => 'Post',
        'product' => 'Product',
    ],
    'livewire' => [
        'demo_title' => 'Livewire table demo',
        'demo_description' => 'The tables below keep filters, ordering, and pagination in the component while delegating text search to Search Engine.',
        'search' => 'Search',
        'status' => 'Status',
        'all_statuses' => 'All statuses',
        'order_by' => 'Order by',
        'direction' => 'Direction',
        'desc' => 'Descending',
        'asc' => 'Ascending',
        'reset' => 'Reset',
        'empty' => 'No records found for the current filters.',
        'statuses' => [
            'published' => 'Published',
            'draft' => 'Draft',
        ],
        'order' => [
            'relevance' => 'Relevance',
            'published_at' => 'Publication',
            'name' => 'Name',
            'title' => 'Title',
            'price' => 'Price',
            'status' => 'Status',
        ],
        'columns' => [
            'item' => 'Item',
            'status' => 'Status',
            'price' => 'Price',
            'published_at' => 'Published at',
        ],
        'products' => [
            'title' => 'ProductTable demo',
            'description' => 'Searches name and description, with status filter and table-owned ordering.',
            'placeholder' => 'Search products, media, store...',
        ],
        'posts' => [
            'title' => 'PostTable demo',
            'description' => 'Searches title, subtitle, excerpt, and body while preserving filters and pagination.',
            'placeholder' => 'Search posts, editor, visits...',
        ],
    ],
    'demo_edit' => [
        'note' => 'This page is a demonstration route. In a real project, it would be replaced by the edit screen for the model found in Spotlight.',
        'back' => 'Back to module',
        'posts' => [
            'type' => 'Demo post',
            'title' => 'Demo post edit',
            'description' => 'Destination used to validate the edit action for post results.',
        ],
        'products' => [
            'type' => 'Demo product',
            'title' => 'Demo product edit',
            'description' => 'Destination used to validate the edit action for product results.',
        ],
    ],
    'admin' => [
        'dashboard' => [
            'summary' => 'Overview of the demo dashboard.',
            'keywords' => ['start', 'home', 'panel', 'dashboard', 'admin'],
        ],
        'helpers' => [
            'title' => 'Helpers Summary',
            'summary' => 'Documentation and examples for the dashboard global helpers.',
            'keywords' => ['helpers', 'functions', 'utilities', 'documentation', 'core'],
        ],
        'modules' => [
            'title' => 'Modules Summary',
            'summary' => 'Extra modules available in the admin flow.',
            'keywords' => ['modules', 'extras', 'components', 'demo'],
        ],
        'image-preview' => [
            'summary' => 'Image preview for create and edit flows.',
            'keywords' => ['image', 'preview', 'upload', 'create', 'edit'],
        ],
        'visits' => [
            'summary' => 'Standalone visit tracking and popularity metrics.',
            'keywords' => ['visits', 'popularity', 'views', 'metrics', 'ranking'],
        ],
        'notifications-ui' => [
            'summary' => 'Visual interface for admin notifications.',
            'keywords' => ['notifications', 'alerts', 'bell', 'dropdown', 'modal'],
        ],
        'maintenance-mode' => [
            'summary' => 'Control the public availability of the site.',
            'keywords' => ['maintenance', 'site offline', '503', 'wordpress', 'site online'],
        ],
        'site-preview' => [
            'summary' => 'Public example route protected by maintenance mode.',
            'keywords' => ['site', 'preview', 'web', 'public', 'maintenance'],
        ],
        'profile' => [
            'summary' => 'Manage the basic account information.',
            'keywords' => ['profile', 'account', 'user', 'email', 'avatar'],
        ],
        'account-notifications' => [
            'summary' => 'Account notification preferences.',
            'keywords' => ['notifications', 'preferences', 'account', 'alerts'],
        ],
        'security' => [
            'summary' => 'Account security settings.',
            'keywords' => ['security', 'password', 'login', 'fingerprint', 'account'],
        ],
    ],
];
