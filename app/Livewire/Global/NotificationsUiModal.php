<?php

namespace App\Livewire\Global;

use App\Support\NotificationsUiDemoData;
use LivewireUI\Modal\ModalComponent;

class NotificationsUiModal extends ModalComponent
{
    public string $filter = 'unread';

    public array $notifications = [];

    public function mount(): void
    {
        $this->notifications = NotificationsUiDemoData::notifications();
    }

    public static function modalMaxWidth(): string
    {
        return '4xl';
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
        $this->markAsRead($notificationId);
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
        return view('livewire.global.notifications-ui-modal', [
            'modalNotifications' => $this->modalNotifications(),
            'unreadCount' => $this->unreadCount(),
        ]);
    }

    private function isUnread(array $notification): bool
    {
        return blank($notification['read_at'] ?? null);
    }
}
