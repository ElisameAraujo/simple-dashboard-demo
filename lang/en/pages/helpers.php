<?php

return [
    'index' => [
        'title' => 'Helpers',
        'kicker' => 'Base structure',
        'heading' => 'Helpers',
        'description' => 'Reusable utility classes that keep common dashboard behavior explicit, discoverable, and easy to call from controllers, views, Livewire components, and services.',
        'method_count' => '{1} :count method|[2,*] :count methods',
    ],
    'actions' => [
        'back' => 'Back to helpers',
    ],
    'sections' => [
        'how_it_works' => [
            'title' => 'How it works',
            'description' => 'Purpose and normal usage flow for this helper.',
        ],
        'methods' => [
            'title' => 'Available methods',
            'description' => 'Public API reflected from the helper class currently registered in config/helpers.php.',
        ],
        'example' => [
            'title' => 'Usage example',
            'description' => 'A direct call shape you can reuse in the dashboard.',
        ],
        'output' => [
            'title' => 'Output',
            'description' => 'Expected result for the example above.',
        ],
    ],
    'methods' => [
        'name' => 'Method',
        'signature' => 'Signature',
        'return' => 'Return',
        'parameters' => 'Parameters',
        'no_parameters' => 'This method does not receive parameters.',
        'example' => 'Example',
        'fallback_summary' => 'Runs the :method method from this helper.',
        'fallback_parameter' => 'Value used by the :parameter parameter.',
    ],
    'parameter_descriptions' => [
        'charactersToMask' => 'Number of characters that will be replaced with asterisks.',
        'className' => 'CSS class applied to the generated code block.',
        'cols' => 'Column distribution for the generated grid.',
        'column' => 'Authenticated user column to read.',
        'count' => 'Quantity used to generate items or calculate pluralization.',
        'currency' => 'Currency code used for formatting.',
        'customName' => 'Optional name for the downloaded file.',
        'date' => 'Input date that will be formatted or compared.',
        'default' => 'Value returned when the main information does not exist.',
        'disk' => 'Disk configured in config/filesystems.php.',
        'email' => 'Email address that will be handled.',
        'endDate' => 'End date used in the difference calculation.',
        'except' => 'File or list of files that should be skipped during import.',
        'field' => 'Validation field that will be inspected.',
        'file' => 'File or relative path inside the disk.',
        'filename' => 'Route filename without the .php extension.',
        'folders' => 'Folder or list of folders inside routes/.',
        'gender' => 'Gender used by Portuguese ordinals. Use m for masculine or f for feminine.',
        'height' => 'Height used in the generated HTML.',
        'id' => 'Column that represents the user identifier.',
        'level' => 'HTML heading level, such as 1, 2, or 3.',
        'limit' => 'Numeric limit applied to the method.',
        'locale' => 'Locale used to format the output.',
        'name' => 'Column or name used to build the output.',
        'newFile' => 'New file that will replace the old file.',
        'notificationId' => 'Notification ID that will be updated or removed.',
        'number' => 'Number that will be formatted.',
        'oldFile' => 'Old file path that will be removed.',
        'path' => 'Relative media path inside the disk.',
        'permission' => 'Permission checked against the authenticated user.',
        'placeholder' => 'Fallback asset used when the media does not exist.',
        'position' => 'Mask position in the text, such as start, middle, or end.',
        'provider' => 'Provider used in the video embed.',
        'role' => 'Role checked against the authenticated user.',
        'ruleName' => 'Rule name to search for, such as max or min.',
        'rulesSource' => 'Rules array or class that exposes formRules().',
        'startDate' => 'Start date used in the difference calculation.',
        'string' => 'Text or pluralization key.',
        'subfolder' => 'Subfolder inside the App\\Notifications namespace.',
        'subfolders' => 'Subfolder or list of subfolders inside the disk.',
        'text' => 'Text that will be cleaned, counted, limited, or transformed.',
        'type' => 'Notification class name.',
        'value' => 'Numeric value that will be formatted.',
        'width' => 'Width used in the generated HTML.',
        'withRandomLinks' => 'Whether generated paragraphs should include fake links.',
    ],
    'helpers' => [
        'date-helper' => [
            'name' => 'DateHelper',
            'description' => 'Formats dates, relative intervals, and email-friendly date labels with locale-aware output.',
            'works' => [
                'Use DateHelper when dates need to be rendered for humans instead of stored or compared.',
                'The helper resolves the requested locale, loads the project date translations, and applies the application timezone before formatting.',
            ],
            'example' => [
                'usage' => [
                    "DateHelper::simpleDate('2026-05-19', 'en_US');",
                ],
                'output' => [
                    '05/19/2026',
                ],
            ],
        ],
        'disk-helper' => [
            'name' => 'DiskHelper',
            'description' => 'Saves, replaces, removes, locates, and sizes files on configured Laravel disks.',
            'works' => [
                'Use DiskHelper when a feature receives uploads and only needs to persist the relative path.',
                'The disk comes first and optional subfolders are only used to organize paths inside that disk.',
            ],
            'example' => [
                'usage' => [
                    "\$path = DiskHelper::saveFile(\$photo, 'public', 'avatars');",
                ],
                'output' => [
                    'avatars/profile-20260521103000.jpg',
                ],
            ],
        ],
        'html-helper' => [
            'name' => 'HTMLHelper',
            'description' => 'Builds fake HTML blocks for demos, editor previews, and content placeholders.',
            'works' => [
                'HTMLHelper starts with make(), then chains builder methods such as heading(), paragraphs(), lists(), images(), and tables.',
                'Call generate() at the end of the chain to return the final HTML string.',
            ],
            'example' => [
                'usage' => [
                    'echo HTMLHelper::make()',
                    '    ->heading(2)',
                    '    ->paragraphs(1)',
                    '    ->generate();',
                ],
                'output' => [
                    '<h2>Example Title</h2><p>Generated paragraph...</p>',
                ],
            ],
        ],
        'media-helper' => [
            'name' => 'MediaHelper',
            'description' => 'Checks media existence and resolves display, download, path, and MIME information.',
            'works' => [
                'Use MediaHelper when a stored media path needs to become a URL, a download response, or a readable asset path.',
                'It protects the UI by returning placeholders or translated errors when the requested media is unavailable.',
            ],
            'example' => [
                'usage' => [
                    "MediaHelper::showMedia('avatars/user.jpg', 'public', 'img/placeholders/avatars/default-avatar.jpg');",
                ],
                'output' => [
                    '/storage/public/avatars/user.jpg',
                ],
            ],
        ],
        'notification-helper' => [
            'name' => 'NotificationHelper',
            'description' => 'Reads, counts, marks, and deletes authenticated user notifications.',
            'works' => [
                'Use NotificationHelper in headers, menus, and panels that need notification state from the logged-in user.',
                'Every read method returns an empty collection or zero when there is no authenticated user.',
            ],
            'example' => [
                'usage' => [
                    'NotificationHelper::allUnreadNotificationsCount();',
                ],
                'output' => [
                    '3',
                ],
            ],
        ],
        'number-helper' => [
            'name' => 'NumberHelper',
            'description' => 'Formats compact numbers, prices, currencies, areas, and ordinals by locale.',
            'works' => [
                'Use NumberHelper when a number is part of the user interface and needs locale-specific symbols or units.',
                'The helper normalizes locale input such as pt-BR or en_US before applying its formatting maps.',
            ],
            'example' => [
                'usage' => [
                    "NumberHelper::priceFormat(1299.9, 'en_US');",
                ],
                'output' => [
                    '$1,299.90',
                ],
            ],
        ],
        'route-helper' => [
            'name' => 'RouteHelper',
            'description' => 'Imports route files by folder and exposes a small route inventory helper.',
            'works' => [
                'Use RouteHelper to keep route files split by admin area or feature without adding repeated require statements.',
                'The demo itself uses this helper to load admin dashboard, helpers, and profile routes.',
            ],
            'example' => [
                'usage' => [
                    "RouteHelper::importRoutesFromFolder('admin', 'helpers');",
                ],
                'output' => [
                    'routes/demo/helpers/*.php loaded',
                ],
            ],
        ],
        'rule-helper' => [
            'name' => 'RuleHelper',
            'description' => 'Extracts values from Laravel validation rules such as max:120 or min:3.',
            'works' => [
                'Use RuleHelper when UI text needs to display the same numeric limit already defined in validation rules.',
                'It accepts either a rules array or a class that exposes formRules().',
            ],
            'example' => [
                'usage' => [
                    "\$rules = ['title' => 'required|string|max:120'];",
                    "RuleHelper::extractValue('title', 'max', \$rules);",
                ],
                'output' => [
                    '120',
                ],
            ],
        ],
        'text-helper' => [
            'name' => 'TextHelper',
            'description' => 'Normalizes, limits, counts, sanitizes, pluralizes, and transforms text.',
            'works' => [
                'Use TextHelper when string cleanup needs to be consistent across forms, comments, imports, and public output.',
                'Some methods are locale-aware, so names and plurals can follow the active language rules.',
            ],
            'example' => [
                'usage' => [
                    "TextHelper::normalizeNames('  maria   da silva  ', 'pt-BR');",
                ],
                'output' => [
                    'Maria da Silva',
                ],
            ],
        ],
        'user-helper' => [
            'name' => 'UserHelper',
            'description' => 'Provides shortcuts for authenticated user data, avatar output, summaries, roles, and permissions.',
            'works' => [
                'Use UserHelper in admin views and components that need a small piece of the authenticated user without repeating Auth checks.',
                'Permission methods depend on spatie/laravel-permission and return safe defaults when no user is logged in.',
            ],
            'example' => [
                'usage' => [
                    "UserHelper::maskEmail('john.doe@example.com', 3, 'middle');",
                ],
                'output' => [
                    'john***e@example.com',
                ],
            ],
        ],
    ],
];
