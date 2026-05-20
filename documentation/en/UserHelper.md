# 🧩 UserHelper

The UserHelper provides utility functions to access information about the authenticated user, generate derived data (such as initials, avatar fallback, abbreviated name), manipulate email, and integrate with the Spatie/Laravel Permission package.

It centralizes common user-related operations, reduces code duplication, and keeps the dashboard cleaner and more consistent.

---

# 📂 Available Functions

---

## `userLogged(): bool`

Checks if there is an authenticated user.

### Example

```php
if (UserHelper::userLogged()) {
    // Authenticated user
}
```

---

## `info(string $column, $default = null)`

Returns any column of the authenticated user.

### Parameters

| Parameter  | Type   | Description                                |
| ---------- | ------ | ------------------------------------------ |
| `$column`  | string | Column name in the User model              |
| `$default` | mixed  | Value returned if the column doesn't exist |

### Example

```php
UserHelper::info('created_at');
//2025-08-17 12:36:44
```

---

## `userIsActive(string $column = 'active'): bool`

Checks if a boolean column indicates that the user is active.

### Example

```php
UserHelper::userIsActive(); // uses "active"
UserHelper::userIsActive('status'); // custom column
```

---

## `userId(string $column = 'id')`

Returns the user's ID.

---

## `username(string $column = 'name')`

Returns the user's full name.

---

## `userFirstName(string $column = 'name')`

Returns only the first name.

### Example

```php
UserHelper::userFirstName(); // "John"
```

---

## `userShortName(string $column = 'name')`

Returns name + abbreviated last name.

### Example

```php
UserHelper::userShortName();
//"John S."
```

---

## `userAvatar(string $column = 'avatar', string $disk = 'public')`

Returns the final URL of the avatar using the `MediaHelper` class.

---

## `userAvatarPath(string $column = 'avatar')`

Returns only the path saved in the database.

---

## `userAvatarFallback(string $column = 'name'): array`

Generates data for a fallback avatar for when the user doesn't have a photo defined.

```php

UserHelper::userAvatarFallback(); // John Paul

// Return
[
    'initials' => 'JP',
    'color' => '#3498db'
]
```

#### Usage example on Front-End

```html
<div style="background: {{ $avatar['color'] }}">{{ $avatar['initials'] }}</div>
```

---

## `userEmail(string $column = 'email')`

Returns the user's email.

---

## `emailDomain(string $column = 'email')`

Returns the email domain.

```php
UserHelper::emailDomain(); // "gmail.com"
```

---

## `maskEmail(string $email, ?int $charactersToMask = null, ?string $position = null)`

Masks the email for safe display, allowing customization of **how many characters** will be masked and **where** the mask will be applied.

The function is complete and allows you to define:

-   If **no parameters** are passed → **masks the entire email before @**
-   If `charactersToMask = 0` → **ignores the parameter and masks the entire email before @**
-   If `charactersToMask` is greater than the available size → **masks everything**
-   `position` only works when `charactersToMask > 0`
-   Possible positions:
    -   `start` → mask at the beginning
    -   `middle` → mask in the middle
    -   `end` (default) → mask at the end

### Examples

#### 🔹 Without parameters (mask everything)

```php
UserHelper::maskEmail('myemail@gmail.com');
// ********@gmail.com
```

#### 🔹 charactersToMask = 0 (ignore and mask everything)

```php
UserHelper::maskEmail('myemail@gmail.com', 0);
// ********@gmail.com
```

#### 🔹 Mask at the beginning (`start`)

```php
UserHelper::maskEmail('myemailforproject@gmail.com', 4, 'start');
// ****mailforproject@gmail.com
```

#### 🔹 Mask at the end (`end`)

```php
UserHelper::maskEmail('myemailforproject@gmail.com', 4);
// myemailforproje****@gmail.com
```

#### 🔹 Mask in the middle (`middle`)

```php
UserHelper::maskEmail('myemailforproject@gmail.com', 4, 'middle');
// myemailfor****ject@gmail.com
```

#### 🔹 When `charactersToMask` exceeds the limit of available characters, it will mask all available characters before `@`

```php
UserHelper::maskEmail('email@gmail.com', 10);
// ****@gmail.com
```

---

## `sanitizeEmail(string $email)`

Cleans an email by removing invalid characters and converting to lowercase.

### Example

```php
UserHelper::sanitizeEmail(" JOAO@GMAIL.COM  ");
// "joao@gmail.com"
```

---

## `userSummary()`

Returns a simple array with:

```php
UserHelper::userSummary();

// Return
[
    'id' => 1,
    'name' => 'John Silva',
    'email' => 'john@gmail.com'
]
```

---

## `userShortSummary()`

Returns a short string:

```php
UserHelper::userShortSummary();
// John S. — john@gmail.com
```

---

# Integration with `spatie/laravel-permission`

If you intend to use the `spatie/laravel-permission` package that is included in this template, this helper already brings some functions to accelerate your work with it.

---

## `userHasRole(string $role): bool`

Checks if the user has a role.

---

## `userHasPermission(string $permission): bool`

Checks if the user has a permission.

---

## `userRoles(): array`

Returns an array with all of the user's roles.

### Example

```php
UserHelper::userRoles();

// ["admin", "editor"]
```

---

## `userPermissions(): array`

Returns an array of all user permissions.

```
UserHelper::userPermissions();

// ["create", "edit", "delete"]
```

---

## `allRoles()`

Returns all existing permissions in the `Permission` model from _Laravel Permission_, useful for use in `selects` when assigning a role to a user.

---

## `allPermissions()`

Returns all existing roles in the `Role` model from _Laravel Permission_, useful for use in `selects` when assigning one or more permissions to a user.
