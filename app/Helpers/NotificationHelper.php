<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class NotificationHelper
{
    /**
     * `unreadNotificationsByType`:
     * Lists unread notifications of a specific notification class.
     * @param string $type Notification class name or fully qualified class name.
     * @param string|null $subfolder Optional subfolder within App\Notifications.
     * @param int|null $limit Optional maximum number of records returned.
     * @return Collection
     */
    public static function unreadNotificationsByType(string $type, ?string $subfolder = null, ?int $limit = 5): Collection
    {
        if (!Auth::check()) {
            return collect();
        }

        $query = Auth::user()
            ->unreadNotifications()
            ->where('type', self::notificationClass($type, $subfolder))
            ->latest('created_at');

        return self::limit($query, $limit)->get();
    }

    /**
     * `unreadNotificationsByTypeCount`:
     * Counts unread notifications of a specific notification class.
     * @param string $type Notification class name or fully qualified class name.
     * @param string|null $subfolder Optional subfolder within App\Notifications.
     * @return int
     */
    public static function unreadNotificationsByTypeCount(string $type, ?string $subfolder = null): int
    {
        if (!Auth::check()) {
            return 0;
        }

        return Auth::user()
            ->unreadNotifications()
            ->where('type', self::notificationClass($type, $subfolder))
            ->count();
    }

    /**
     * `allUnreadNotifications`:
     * Lists unread notifications for the authenticated user.
     * @param int|null $limit Optional maximum number of records returned. Null returns all unread notifications.
     * @return Collection
     */
    public static function allUnreadNotifications(?int $limit = null): Collection
    {
        if (!Auth::check()) {
            return collect();
        }

        $query = Auth::user()
            ->unreadNotifications()
            ->latest('created_at');

        return self::limit($query, $limit)->get();
    }

    /**
     * `allUnreadNotificationsCount`:
     * Counts all unread notifications for the authenticated user.
     * @return int
     */
    public static function allUnreadNotificationsCount(): int
    {
        return Auth::check()
            ? Auth::user()->unreadNotifications()->count()
            : 0;
    }

    /**
     * `latestNotifications`:
     * Lists the latest notifications, read or unread, for dropdown previews.
     * @param int|null $limit Maximum number of records returned. Null returns all notifications.
     * @return Collection
     */
    public static function latestNotifications(?int $limit = 10): Collection
    {
        if (!Auth::check()) {
            return collect();
        }

        $query = Auth::user()
            ->notifications()
            ->latest('created_at');

        return self::limit($query, $limit)->get();
    }

    /**
     * Builds the stored notification type value.
     */
    private static function notificationClass(string $type, ?string $subfolder = null): string
    {
        $type = trim($type, '\\');

        if (str_contains($type, '\\')) {
            return $type;
        }

        $base = 'App\\Notifications';

        if ($subfolder) {
            $base .= '\\' . trim($subfolder, '\\/');
        }

        return $base . '\\' . $type;
    }

    /**
     * Applies a positive limit to a notification query.
     */
    private static function limit($query, ?int $limit)
    {
        return $limit !== null && $limit > 0
            ? $query->limit($limit)
            : $query;
    }
}
