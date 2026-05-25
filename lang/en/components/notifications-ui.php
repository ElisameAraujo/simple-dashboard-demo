<?php

return [
    'title' => 'Notifications',
    'trigger_label' => 'Open notifications preview',
    'backend_free' => 'UI only',
    'opened' => 'Opened: :title',
    'unread_count' => '{1}:count unread notification|[2,*]:count unread notifications',
    'actions' => [
        'mark_all_read' => 'Mark all as read',
        'mark_read' => 'Mark as read',
        'view_all' => 'View all notifications',
        'delete_read' => 'Delete read',
        'delete' => 'Delete notification',
        'close' => 'Close notifications',
    ],
    'filters' => [
        'label' => 'Notification filters',
        'unread' => 'Unread',
        'all' => 'All',
        'read' => 'Read',
    ],
    'modal' => [
        'title' => 'Notifications',
        'description' => 'Mocked admin notifications for the demo flow.',
        'footer' => 'Connect these actions to your own backend.',
    ],
    'empty' => [
        'dropdown' => 'No new notifications.',
        'modal' => 'No notifications to display.',
    ],
    'fallback' => [
        'title' => 'Notification',
        'author' => 'System',
        'label' => 'Notification',
    ],
    'fake' => [
        'order' => [
            'title' => 'Order approved',
            'description' => 'The newest order was approved and is ready for fulfillment.',
            'author' => 'Sales',
            'label' => 'Order',
            'time' => '2 minutes ago',
        ],
        'message' => [
            'title' => 'New message',
            'description' => 'A customer sent a new message through the contact form.',
            'author' => 'Inbox',
            'label' => 'Message',
            'time' => '18 minutes ago',
        ],
        'comment' => [
            'title' => 'Comment pending',
            'description' => 'A new comment is waiting for moderation in the admin panel.',
            'author' => 'Blog',
            'label' => 'Comment',
            'time' => '41 minutes ago',
        ],
        'backup' => [
            'title' => 'Backup completed',
            'description' => 'The scheduled backup completed successfully.',
            'author' => 'System',
            'label' => 'System',
            'time' => '2 hours ago',
        ],
    ],
];
