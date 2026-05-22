<?php

namespace App\Helpers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserHelper
{
    /**
     * Returns the authenticated user model.
     */
    private static function userModel(): ?Authenticatable
    {
        return Auth::user();
    }

    /**
     * Checks if a user is authenticated.
     */
    public static function userLogged(): bool
    {
        return Auth::check();
    }

    /**
     * Returns a direct attribute from the authenticated user.
     * @param string $column User attribute name.
     * @param mixed $default Value returned when the user or attribute does not exist.
     * @return mixed User attribute value or default.
     */
    public static function info(string $column, mixed $default = null): mixed
    {
        $user = self::userModel();

        if (!$user instanceof Model || !array_key_exists($column, $user->getAttributes())) {
            return $default;
        }

        return $user->getAttribute($column) ?? $default;
    }

    /**
     * Checks if a user attribute matches the expected active value.
     * @param string $column Attribute that represents the user status.
     * @param mixed $activeValue Value considered active for the project.
     * @return bool Whether the user is considered active.
     */
    public static function userIsActive(string $column = 'active', mixed $activeValue = true): bool
    {
        $value = self::info($column);

        if ($value === null) {
            return false;
        }

        return self::valuesMatch($value, $activeValue);
    }

    /**
     * Returns the authenticated user ID.
     * @param string $column Attribute used as identifier.
     * @return mixed User ID or null.
     */
    public static function userId(string $column = 'id'): mixed
    {
        return self::info($column);
    }

    /**
     * Returns the authenticated user's name.
     * @param string $column Attribute used as name.
     * @return string|null User name.
     */
    public static function username(string $column = 'name'): ?string
    {
        $name = self::info($column);

        return is_string($name) && trim($name) !== '' ? $name : null;
    }

    /**
     * Returns the authenticated user's first name.
     * @param string $column Attribute used as name.
     * @return string|null First name.
     */
    public static function userFirstName(string $column = 'name'): ?string
    {
        $name = self::username($column);

        return $name ? TextHelper::firstName($name) : null;
    }

    /**
     * Returns the user's first name plus abbreviated last name.
     * @param string $column Attribute used as name.
     * @return string|null Short name.
     */
    public static function userShortName(string $column = 'name'): ?string
    {
        $name = self::username($column);

        if (!$name) {
            return null;
        }

        $parts = preg_split('/\s+/u', TextHelper::normalizeWhitespace($name), -1, PREG_SPLIT_NO_EMPTY);

        if (!$parts) {
            return null;
        }

        return count($parts) > 1
            ? $parts[0] . ' ' . Str::upper(Str::substr(end($parts), 0, 1)) . '.'
            : $parts[0];
    }

    /**
     * Returns the authenticated user's email.
     * @param string $column Attribute used as email.
     * @return string|null User email.
     */
    public static function userEmail(string $column = 'email'): ?string
    {
        $email = self::info($column);

        return is_string($email) && trim($email) !== '' ? $email : null;
    }

    /**
     * Returns the domain from the authenticated user's email.
     * @param string $column Attribute used as email.
     * @return string|null Email domain.
     */
    public static function emailDomain(string $column = 'email'): ?string
    {
        $email = self::userEmail($column);

        return $email && str_contains($email, '@')
            ? explode('@', $email, 2)[1]
            : null;
    }

    /**
     * Masks the local part of an email address.
     * @param string $email Email that will be masked.
     * @param int|null $charactersToMask Number of characters to mask.
     * @param string|null $position Position to mask: start, middle, or end.
     * @return string Masked email.
     */
    public static function maskEmail(string $email, ?int $charactersToMask = null, ?string $position = null): string
    {
        if (!str_contains($email, '@')) {
            return $email;
        }

        [$local, $domain] = explode('@', $email, 2);

        if ($charactersToMask === null || $charactersToMask <= 0) {
            $charactersToMask = strlen($local);
        }

        if ($charactersToMask >= strlen($local)) {
            return self::maskAll($local) . '@' . $domain;
        }

        return match (self::normalizePosition($position)) {
            'start' => self::maskStart($local, $charactersToMask) . '@' . $domain,
            'middle' => self::maskMiddle($local, $charactersToMask) . '@' . $domain,
            default => self::maskEnd($local, $charactersToMask) . '@' . $domain,
        };
    }

    /**
     * Sanitizes an email by removing invalid characters and converting it to lowercase.
     * @param string $email Email that will be sanitized.
     * @return string Sanitized email.
     */
    public static function sanitizeEmail(string $email): string
    {
        return Str::lower(filter_var(trim($email), FILTER_SANITIZE_EMAIL));
    }

    /**
     * Returns the user's avatar URL when an avatar attribute exists.
     * @param string $column Attribute that stores the avatar path.
     * @param string $disk Storage disk.
     * @param string|null $placeholder Public placeholder path used when the avatar does not exist.
     * @return string|null Avatar URL or placeholder URL.
     */
    public static function userAvatar(string $column = 'avatar', string $disk = 'public', ?string $placeholder = null): ?string
    {
        $path = self::userAvatarPath($column);

        if (!$path) {
            return $placeholder ? asset($placeholder) : null;
        }

        return MediaHelper::showMedia($path, $disk, $placeholder);
    }

    /**
     * Returns the user's avatar path without resolving a URL.
     * @param string $column Attribute that stores the avatar path.
     * @return string|null Avatar path.
     */
    public static function userAvatarPath(string $column = 'avatar'): ?string
    {
        $path = self::info($column);

        return is_string($path) && trim($path) !== '' ? $path : null;
    }

    /**
     * Generates fallback avatar data with initials and a stable color.
     * @param string $column Attribute used as name.
     * @return array{initials: string, color: string}
     */
    public static function userAvatarFallback(string $column = 'name'): array
    {
        $name = self::username($column);
        $initials = $name ? TextHelper::initials($name, 2) : '?';

        $colors = ['#1abc9c', '#3498db', '#9b59b6', '#e67e22', '#e74c3c'];
        $userId = self::userId();
        $index = is_numeric($userId) ? (int) $userId % count($colors) : 1;

        return [
            'initials' => $initials,
            'color' => $colors[$index] ?? '#3498db',
        ];
    }

    /**
     * Returns a simple authenticated user summary.
     * @param string $id Attribute used as identifier.
     * @param string $name Attribute used as name.
     * @param string $email Attribute used as email.
     * @return array{id: mixed, name: string|null, email: string|null}
     */
    public static function userSummary(string $id = 'id', string $name = 'name', string $email = 'email'): array
    {
        return [
            'id' => self::userId($id),
            'name' => self::username($name),
            'email' => self::userEmail($email),
        ];
    }

    /**
     * Returns a compact authenticated user summary.
     * @param string $name Attribute used as name.
     * @param string $email Attribute used as email.
     * @return string|null Compact user summary.
     */
    public static function userShortSummary(string $name = 'name', string $email = 'email'): ?string
    {
        $name = self::userShortName($name);
        $email = self::userEmail($email);

        return $name && $email ? "{$name} — {$email}" : null;
    }

    /**
     * Checks if the authenticated user has a role when Spatie roles are implemented.
     * @param string $role Role name.
     * @return bool Whether the user has the role.
     */
    public static function userHasRole(string $role): bool
    {
        $user = self::userModel();

        return $user && method_exists($user, 'hasRole') && $user->hasRole($role);
    }

    /**
     * Checks if the authenticated user has a permission.
     * @param string $permission Permission name.
     * @return bool Whether the user has the permission.
     */
    public static function userHasPermission(string $permission): bool
    {
        $user = self::userModel();

        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasPermissionTo')) {
            return $user->hasPermissionTo($permission);
        }

        return method_exists($user, 'can') && $user->can($permission);
    }

    /**
     * Returns role names when Spatie roles are implemented.
     * @return array<int, string>
     */
    public static function userRoles(): array
    {
        $user = self::userModel();

        if (!$user || !method_exists($user, 'getRoleNames')) {
            return [];
        }

        return $user->getRoleNames()->values()->all();
    }

    /**
     * Returns all permission names when Spatie permissions are implemented.
     * @return array<int, string>
     */
    public static function userPermissions(): array
    {
        $user = self::userModel();

        if (!$user || !method_exists($user, 'getAllPermissions')) {
            return [];
        }

        return $user->getAllPermissions()->pluck('name')->values()->all();
    }

    /**
     * Returns all permission names registered by Spatie.
     * @return Collection<int, string>
     */
    public static function allPermissions(): Collection
    {
        return Permission::query()->pluck('name');
    }

    /**
     * Returns all role names registered by Spatie.
     * @return Collection<int, string>
     */
    public static function allRoles(): Collection
    {
        return Role::query()->pluck('name');
    }

    private static function valuesMatch(mixed $value, mixed $expected): bool
    {
        if (is_bool($expected)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === $expected;
        }

        if (is_scalar($value) && is_scalar($expected)) {
            return (string) $value === (string) $expected;
        }

        return $value === $expected;
    }

    private static function maskAll(string $local): string
    {
        return str_repeat('*', strlen($local));
    }

    private static function normalizePosition(?string $position): string
    {
        $position = strtolower($position ?? 'end');

        return in_array($position, ['start', 'middle', 'end'], true)
            ? $position
            : 'end';
    }

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
        $middleStart = (int) ceil($length / 2);

        if ($middleStart + $count > $length) {
            $middleStart = $length - $count;
        }

        $start = substr($local, 0, $middleStart);
        $end = substr($local, $middleStart + $count);

        return $start . str_repeat('*', $count) . $end;
    }
}
