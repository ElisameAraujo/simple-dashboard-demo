<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class NotificationHelper
{
    /**
     * `namespace`:
     * Generates the full path of the notification class
     * @param string $type Name of the notification class
     * @param string|null $subfolder Subfolder within App\Notifications
     * @return string
     */
    protected static function namespace(string $type, ?string $subfolder = null): string
    {
        $base = 'App\\Notifications';

        if ($subfolder) {
            $base .= '\\' . trim($subfolder, '\\/');
        }

        return $base . '\\' . $type;
    }

    /**
     * `unreadNotificationsByType`:
     * Lists unread notifications by type
     * @param string $type Notification class name
     * @param string|null $subfolder Subfolder within App\Notifications
     * @param int|null $limit Limit of returned records
     * @return \Illuminate\Support\Collection
     */
    public static function unreadNotificationsByType(string $type, ?string $subfolder = null, ?int $limit = 5)
    {
        $class = self::namespace($type, $subfolder);

        return Auth::check()
            ? Auth::user()
            ->unreadNotifications()
            ->where('type', $class)
            ->latest('created_at')
            ->limit($limit)
            ->get()
            : collect();
    }

    /**
     * `unreadNotificationsByTypeCount`:
     * Total number of unread notifications by type
     * @param string $type Notification class name
     * @param string|null $subfolder Subfolder within App\Notifications
     * @return int
     */
    public static function unreadNotificationsByTypeCount(string $type, ?string $subfolder = null): int
    {
        $class = self::namespace($type, $subfolder);

        return Auth::check()
            ? Auth::user()->unreadNotifications->where('type', $class)->count()
            : 0;
    }

    /**
     * `allUnreadNotifications`:
     * Lists all unread notifications
     * @param int|null $limit Limit of returned records
     * @return \Illuminate\Support\Collection
     */
    public static function allUnreadNotifications(?int $limit = 10)
    {
        return Auth::check()
            ? Auth::user()
            ->unreadNotifications()
            ->latest('created_at')
            ->limit($limit)
            ->get()
            : collect();
    }

    /**
     * `allUnreadNotificationsCount`:
     * Total number of unread notifications for the user
     * @return int
     */
    public static function allUnreadNotificationsCount(): int
    {
        return Auth::check()
            ? Auth::user()->unreadNotifications->count()
            : 0;
    }

    /**
     * `markAllAsRead`:
     * Marks all unread notifications as read
     * @return void
     */
    public static function markAllAsRead(): void
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
        }
    }

    /**
     * `markAllAsReadByType`:
     * Marks all unread notifications of a specific type as read
     * @param string $type Notification class name
     * @param string|null $subfolder Subfolder within App\Notifications
     * @return void
     */
    public static function markAllAsReadByType(string $type, ?string $subfolder = null): void
    {
        if (Auth::check()) {
            $class = self::namespace($type, $subfolder);
            Auth::user()->unreadNotifications->where('type', $class)->markAsRead();
        }
    }

    /**
     * `latestNotifications`:
     * Lists the latest notifications (read or unread)
     * @param int|null $limit Limit of returned records
     * @return \Illuminate\Support\Collection
     */
    public static function latestNotifications(?int $limit = 10)
    {
        return Auth::check()
            ? Auth::user()
            ->notifications()
            ->latest('created_at')
            ->limit($limit)
            ->get()
            : collect();
    }

    /**
     * `markAsRead`:
     * Marks a specific notification as read
     * @param string $notificationId ID of the notification
     * @return bool
     */
    public static function markAsRead(string $notificationId): bool
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                return true;
            }
        }
        return false;
    }

    /**
     * `markAsUnread`:
     * Marks a specific notification as unread
     * @param string $notificationId ID of the notification
     * @return bool
     */
    public static function markAsUnread(string $notificationId): bool
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->find($notificationId);
            if ($notification && $notification->read_at) {
                $notification->update(['read_at' => null]);
                return true;
            }
        }
        return false;
    }

    /**
     * `deleteNotification`:
     * Removes an specific notification
     * @param string $notificationId ID of the notification
     * @return bool
     */
    public static function deleteNotification(string $notificationId): bool
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->find($notificationId);
            if ($notification) {
                $notification->delete();
                return true;
            }
        }
        return false;
    }
}
