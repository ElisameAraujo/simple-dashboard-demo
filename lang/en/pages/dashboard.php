<?php

return [
    'kicker' => 'Simple Dashboard',
    'intro' => [
        'title' => 'General demo summary',
        'description' => 'This screen centralizes the current panel status, the navigation paths already available, and a few reference links for continuing the implementation.',
    ],
    'actions' => [
        'profile' => 'View profile',
    ],
    'summary' => [
        'stack' => [
            'label' => 'Main stack',
            'description' => 'Base with Livewire, Tailwind CSS, DaisyUI, and FontAwesome.',
        ],
        'helpers' => [
            'label' => 'Documented helpers',
            'description' => 'Utility classes with translatable documentation.',
        ],
        'locales' => [
            'label' => 'Prepared languages',
            'description' => 'Interface with Brazilian Portuguese and English files.',
        ],
        'pages' => [
            'label' => 'Demo pages',
            'description' => 'Dashboard, profile, notifications, and security.',
        ],
    ],
    'sections' => [
        'available_pages' => [
            'title' => 'Available pages',
            'description' => 'Shortcuts to the screens that are already part of the demo.',
        ],
        'next_steps' => [
            'title' => 'Next steps',
            'description' => 'Initial queue for expanding the demo.',
        ],
        'useful_links' => [
            'title' => 'Useful links',
            'description' => 'Quick references for documentation and code.',
        ],
        'helper_docs' => [
            'title' => 'Helper documentation',
            'description' => 'Translated metadata in lang/en/docs/helpers/{helper}.php.',
        ],
    ],
    'demo_pages' => [
        'profile' => [
            'title' => 'Profile',
            'description' => 'Administrative area example for account data and profile image.',
        ],
        'notifications' => [
            'title' => 'Notifications',
            'description' => 'Preferences, pause periods, and checkbox controls.',
        ],
        'security' => [
            'title' => 'Security',
            'description' => 'Visual flow for password, account recovery, and account removal.',
        ],
    ],
    'status' => [
        'available' => 'Available',
    ],
    'useful_links' => [
        'readme' => [
            'label' => 'Project README',
            'description' => 'Overview, installation, and requirements.',
        ],
        'repository' => [
            'label' => 'Repository',
            'description' => 'Source code and public history.',
        ],
    ],
    'next_steps' => [
        'Add administrative pages with tables and filters.',
        'Create form examples with real validation.',
        'Include reusable Livewire components in the demo.',
    ],
];
