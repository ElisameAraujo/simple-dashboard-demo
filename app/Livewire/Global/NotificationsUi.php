<?php

namespace App\Livewire\Global;

use App\Support\NotificationsUiDemoData;
use Livewire\Component;

class NotificationsUi extends Component
{
    public string $scenario = 'dropdown';

    public string $variant = 'demo';

    public string $filter = 'unread';

    public ?string $openedNotificationTitle = null;

    public array $notifications = [];

    public function mount(string $scenario = 'dropdown', string $variant = 'demo'): void
    {
        $this->scenario = $scenario;
        $this->variant = $variant;
        $this->notifications = $scenario === 'empty' ? [] : NotificationsUiDemoData::notifications();
    }

    public function openModal(): void
    {
        $this->dispatch('openModal', component: 'global.notifications-ui-modal');
    }

    public function setFilter(string $filter): void
    {
        if (! in_array($filter, ['all', 'unread', 'read'], true)) {
            return;
        }

        $this->filter = $filter;
    }

    public function openNotification(string $notificationId): void
    {
        $notification = $this->findNotification($notificationId);

        if ($notification === null) {
            return;
        }

        $this->markAsRead($notificationId);
        $this->openedNotificationTitle = $notification['title'];
    }

    public function markAsRead(string $notificationId): void
    {
        foreach ($this->notifications as $index => $notification) {
            if ($notification['id'] !== $notificationId) {
                continue;
            }

            $this->notifications[$index]['read_at'] ??= now()->toDateTimeString();

            return;
        }
    }

    public function markAllAsRead(): void
    {
        foreach ($this->notifications as $index => $notification) {
            $this->notifications[$index]['read_at'] ??= now()->toDateTimeString();
        }
    }

    public function deleteNotification(string $notificationId): void
    {
        $this->notifications = array_values(array_filter(
            $this->notifications,
            fn (array $notification): bool => $notification['id'] !== $notificationId
        ));
    }

    public function deleteRead(): void
    {
        $this->notifications = array_values(array_filter(
            $this->notifications,
            fn (array $notification): bool => $this->isUnread($notification)
        ));
    }

    public function unreadCount(): int
    {
        return count(array_filter($this->notifications, fn (array $notification): bool => $this->isUnread($notification)));
    }

    public function dropdownNotifications(): array
    {
        return array_slice(
            array_values(array_filter($this->notifications, fn (array $notification): bool => $this->isUnread($notification))),
            0,
            5
        );
    }

    public function modalNotifications(): array
    {
        return array_values(array_filter(
            $this->notifications,
            fn (array $notification): bool => match ($this->filter) {
                'unread' => $this->isUnread($notification),
                'read' => ! $this->isUnread($notification),
                default => true,
            }
        ));
    }

    public function render()
    {
        return view('livewire.global.notifications-ui', [
            'dropdownNotifications' => $this->dropdownNotifications(),
            'modalNotifications' => $this->modalNotifications(),
            'unreadCount' => $this->unreadCount(),
        ]);
    }

    private function findNotification(string $notificationId): ?array
    {
        foreach ($this->notifications as $notification) {
            if ($notification['id'] === $notificationId) {
                return $notification;
            }
        }

        return null;
    }

    private function isUnread(array $notification): bool
    {
        return blank($notification['read_at'] ?? null);
    }
}
