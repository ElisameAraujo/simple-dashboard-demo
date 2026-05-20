<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserHelper
{
    /**
     * `userModel`:
     * Returns the complete model of the authenticated user
     */
    private static function userModel()
    {
        return Auth::user();
    }

    /**
     * `userLogged`:
     * Checks if a user is logged in
     */
    public static function userLogged(): bool
    {
        return Auth::check();
    }

    /**
     * `info`:
     * Returns any information from the authenticated user
     * @param string $column Name of the column to be returned.
     * @param mixed $default Default value if the column does not exist.
     * @return mixed
     */
    public static function info(string $column, $default = null)
    {
        return self::userLogged()
            ? (self::userModel()->{$column} ?? $default)
            : $default;
    }

    /**
     * `userIsActive`:
     * Checks if a user's boolean column is active
     * @param string $column Boolean column name (e.g., "active").
     * @return bool
     */
    public static function userIsActive(string $column = 'active'): bool
    {
        return self::info($column, false);
    }

    /**
     * `userId`:
     * Returns the user ID
     * @param string $column Column name (default: "id").
     * @return mixed
     */
    public static function userId(string $column = 'id')
    {
        return self::info($column);
    }

    /**
     * `username`:
     * Returns the user's full name
     * @param string $column Column name (default: "name").
     * @return mixed
     */
    public static function username(string $column = 'name')
    {
        return self::info($column);
    }

    /**
     * `userFirstName`:
     * Returns the user's first name
     * @param string $column Column name (default: "name").
     * @return string|null
     */
    public static function userFirstName(string $column = 'name')
    {
        $name = self::username($column);
        return $name ? explode(' ', trim($name))[0] : null;
    }

    /**
     * `userShortName`:
     * Returns the abbreviated name (e.g., "João S.")
     * @param string $column Column name (default: "name").
     * @return string|null 
     */
    public static function userShortName(string $column = 'name')
    {
        $name = self::username($column);

        if (!$name) {
            return null;
        }

        $parts = explode(' ', trim($name));

        return count($parts) > 1
            ? $parts[0] . ' ' . mb_substr(end($parts), 0, 1) . '.'
            : $parts[0];
    }

    /**
     * `userEmail`:
     * Returns the user's email
     * @param string $column Column name (default: "email").
     * @return mixed
     */
    public static function userEmail(string $column = 'email')
    {
        return self::info($column);
    }

    /**
     * `emailDomain`:
     * Returns the email domain of the user
     * @param string $column Column name (default: "email").
     * @return string|mixed|null
     */
    public static function emailDomain(string $column = 'email'): ?string
    {
        $email = self::userEmail($column);
        return $email ? explode('@', $email)[1] ?? null : null;
    }

    /**
     * `maskEmail`:
     * Hides part of the user's email (e.g., j***@gmail.com)
     * @param string $column Column name (default: "email").
     * @param int|null $charactersToMask Number of characters to mask (default: null = all).
     * @param string|null $position Position to mask (start, middle, end) (default: null = end).
     * @return string|mixed|null
     */
    public static function maskEmail(string $email, ?int $charactersToMask = null, ?string $position = null): string
    {
        if (!str_contains($email, '@')) {
            return $email;
        }

        [$local, $domain] = explode('@', $email, 2);

        // If no parameters were passed → mask everything
        if ($charactersToMask === null && $position === null) {
            return self::maskAll($local) . '@' . $domain;
        }

        // If charactersToMask is 0 → ignore and mask everything
        if ($charactersToMask === 0) {
            $charactersToMask = strlen($local);
        }

        // If charactersToMask is null → assume that it should mask everything
        if ($charactersToMask === null) {
            $charactersToMask = strlen($local);
        }

        // If charactersToMask exceeds the size → mask all
        if ($charactersToMask >= strlen($local)) {
            return self::maskAll($local) . '@' . $domain;
        }

        // Normalize position
        $position = self::normalizePosition($position);

        return match ($position) {
            'start'  => self::maskStart($local, $charactersToMask) . '@' . $domain,
            'middle' => self::maskMiddle($local, $charactersToMask) . '@' . $domain,
            'end'    => self::maskEnd($local, $charactersToMask) . '@' . $domain,
            default  => self::maskEnd($local, $charactersToMask) . '@' . $domain,
        };
    }

    /**
     * `sanitizeEmail`:
     * Sanitizes an email by removing invalid characters and converting to lowercase
     * @param string $email Email to be sanitized.
     * @return string
     */
    public static function sanitizeEmail(string $email): string
    {
        return Str::lower(filter_var($email, FILTER_SANITIZE_EMAIL));
    }

    /**
     * `userAvatar`:
     * Returns the user's avatar (final URL)
     * @param string $column Column name (default: "avatar").
     * @param string $disk Storage disk.
     * @return string|null
     */
    public static function userAvatar(string $column = 'avatar', string $disk = 'public')
    {
        $path = self::info($column);
        return MediaHelper::showMedia($path, $disk);
    }

    /**
     * `userAvatarPath`:
     * Returns the user's avatar path without resolving URL
     * @param string $column Column name (default: "avatar").
     * @return mixed
     */
    public static function userAvatarPath(string $column = 'avatar')
    {
        return self::info($column);
    }

    /**
     * `userAvatarFallback`:
     * Generates data for fallback avatar (initials + color)
     * @param string $column Column of username (default: "name").
     * @return array|mixed
     */
    public static function userAvatarFallback(string $column = 'name'): array
    {
        $name = self::username($column);

        $initials = $name
            ? Str::upper(mb_substr($name, 0, 1))
            : '?';

        $colors = ['#1abc9c', '#3498db', '#9b59b6', '#e67e22', '#e74c3c'];
        $color = $colors[self::userId() % count($colors)] ?? '#3498db';

        return [
            'initials' => $initials,
            'color' => $color,
        ];
    }

    /**
     * `userSummary`
     * Returns a simple summary of the user
     * @param string $id Define the user's ID column (default: id)
     * @param string $name Define the user's name column (default: name)
     * @param string $email Define the user's email column (default: email)
     * @return array
     */
    public static function userSummary(string $id = 'id', string $name = 'name', string $email = 'email'): array
    {
        return [
            'id'    => self::userId($id),
            'name'  => self::username($name),
            'email' => self::userEmail($email),
        ];
    }

    /**
     * `userShortSummary`
     * Returns a short summary of the user (e.g., "João S. — joao@email.com")
     * @param string $name Define the user's name column (default: name)
     * @param string $email Define the user's email column (default: email)
     * @return string|null
     */
    public static function userShortSummary(string $name = 'name', string $email = 'email'): ?string
    {
        $name = self::userShortName($name);
        $email = self::userEmail($email);

        return $name && $email ? "{$name} — {$email}" : null;
    }

    /*-----------------------------------------------------------------------------------------
     *  SPATIE/LARAVEL-PERMISSION
     * 
     * If you intend to use permission and user role management using spatie/laravel-permission, 
     * * these functions will speed up access to some internal package functions
     * ----------------------------------------------------------------------------------------*/

    /**
     * `userHasRole`:
     * Verifies if the user has a role
     * @param string $role Name of the role to check if the user has
     * @return bool
     */
    public static function userHasRole(string $role): bool
    {
        return self::userLogged() && self::userModel()->hasRole($role);
    }

    /**
     * `userHasPermission`:
     * Verifies if the user has a permission
     * @param string $permission Name of the permission to check if the user has
     * @return bool
     */
    public static function userHasPermission(string $permission): bool
    {
        return self::userLogged() && self::userModel()->can($permission);
    }

    /**
     * `userRoles`:
     * Returns an array with all roles of the user
     * @return array
     */
    public static function userRoles(): array
    {
        return self::userLogged()
            ? self::userModel()->roles->pluck('name')->toArray()
            : [];
    }

    /**
     * `userPermissions`:
     * Returns all permissions of the user
     * @return array
     */
    public static function userPermissions(): array
    {
        return self::userLogged()
            ? self::userModel()->permissions->pluck('name')->toArray()
            : [];
    }

    /**
     * `allPermissions`:
     * Returns all permissions existing in the Laravel Permission from Permission model.
     * @return \Illuminate\Support\Collection<int|string, mixed>
     */
    public static function allPermissions()
    {
        return Permission::all()->pluck('name');
    }

    /**
     * `allRoles`:
     * Returns all roles existing in the Laravel Permission's from Role model.
     * @return \Illuminate\Support\Collection<int|string, mixed>
     */
    public static function allRoles()
    {
        return Role::all()->pluck('name');
    }

    private static function maskAll(string $local): string
    {
        return str_repeat('*', strlen($local));
    }

    private static function normalizePosition(?string $position): string
    {
        $position = strtolower($position ?? 'end');

        return in_array($position, ['start', 'middle', 'end'])
            ? $position
            : 'end';
    }

    /*-----------------------------------------------------------------------------------------
     *  PRIVATE FUNCTIONS
     * ----------------------------------------------------------------------------------------*/
    private static function maskStart(string $local, int $count): string
    {
        return str_repeat('*', $count) . substr($local, $count);
    }

    private static function maskEnd(string $local, int $count): string
    {
        return substr($local, 0, strlen($local) - $count) . str_repeat('*', $count);
    }

    private static function maskMiddle(string $local, int $count): string
    {
        $length = strlen($local);

        // Divide o local em duas partes
        $middleStart = (int) ceil($length / 2);

        // Ajusta caso ultrapasse o limite
        if ($middleStart + $count > $length) {
            $middleStart = $length - $count;
        }

        $start = substr($local, 0, $middleStart);
        $end   = substr($local, $middleStart + $count);

        return $start . str_repeat('*', $count) . $end;
    }
}
