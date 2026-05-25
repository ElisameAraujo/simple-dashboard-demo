<?php

namespace App\Support;

class NotificationsUiDemoData
{
    public static function notifications(): array
    {
        return [
            [
                'id' => 'demo-order-approved',
                'title' => __('components/notifications-ui.fake.order.title'),
                'description' => __('components/notifications-ui.fake.order.description'),
                'author' => __('components/notifications-ui.fake.order.author'),
                'label' => __('components/notifications-ui.fake.order.label'),
                'icon' => 'fa-solid fa-bag-shopping',
                'url' => '#',
                'read_at' => null,
                'time_label' => __('components/notifications-ui.fake.order.time'),
            ],
            [
                'id' => 'demo-message',
                'title' => __('components/notifications-ui.fake.message.title'),
                'description' => __('components/notifications-ui.fake.message.description'),
                'author' => __('components/notifications-ui.fake.message.author'),
                'label' => __('components/notifications-ui.fake.message.label'),
                'icon' => 'fa-regular fa-envelope',
                'url' => '#',
                'read_at' => null,
                'time_label' => __('components/notifications-ui.fake.message.time'),
            ],
            [
                'id' => 'demo-comment',
                'title' => __('components/notifications-ui.fake.comment.title'),
                'description' => __('components/notifications-ui.fake.comment.description'),
                'author' => __('components/notifications-ui.fake.comment.author'),
                'label' => __('components/notifications-ui.fake.comment.label'),
                'icon' => 'fa-regular fa-comments',
                'url' => '#',
                'read_at' => null,
                'time_label' => __('components/notifications-ui.fake.comment.time'),
            ],
            [
                'id' => 'demo-backup',
                'title' => __('components/notifications-ui.fake.backup.title'),
                'description' => __('components/notifications-ui.fake.backup.description'),
                'author' => __('components/notifications-ui.fake.backup.author'),
                'label' => __('components/notifications-ui.fake.backup.label'),
                'icon' => 'fa-solid fa-shield-halved',
                'url' => '#',
                'read_at' => now()->subHours(2)->toDateTimeString(),
                'time_label' => __('components/notifications-ui.fake.backup.time'),
            ],
        ];
    }
}
