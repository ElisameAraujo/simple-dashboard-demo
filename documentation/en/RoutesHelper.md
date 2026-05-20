# 🚦 RouteHelper

The **RouteHelper** is a utility class that facilitates the organization and importing of route files in Laravel.  
It offers two main ways to load routes:

-   **importRouteFile** → Imports a specific file
-   **importRoutesFromFolder** → Imports all files from a folder (with optional subfolders and exclusions)

Additionally, it includes a method to list all routes registered in the application.

---

# 📌 General Structure

The helper always works within the folder:

```
routes/
```

And allows you to navigate through subfolders in a simple and predictable way.

---

# 📂 Available Functions

---

### `importRouteFile(string $filename, string|array|null $folders = null)`

Imports **a single route file**, located in `routes/` or in subfolders.

#### **Example 1 — file at the root**

```php
RouteHelper::importRouteFile('admin');
```

Loads:

```
routes/admin.php
```

---

#### **Example 2 — file in a subfolder**

```php
RouteHelper::importRouteFile('users', 'admin');
```

Loads:

```
routes/admin/users.php
```

---

#### **Example 3 — file in multiple subfolder levels**

```php
RouteHelper::importRouteFile('orders', ['ecommerce', 'v2']);
```

Loads:

```
routes/ecommerce/v2/orders.php
```

---

### `importRoutesFromFolder(string $rootFolder, string|array|null $subfolders = null, string|array|null $except = null)`

Imports **all `.php` files** within a specific folder, with support for:

-   optional subfolders
-   exclusion of specific files

This function is ideal when you organize your routes by modules or sections.

#### Structure

-   `$rootFolder`: Root folder within `routes/`
-   `$subfolders` _(optional)_: Receives a string or an array of subfolders where the route file is located. If ignored, it will import route files only from the root of the folder inside `routes/`. If a value is passed, it will only look for files inside the subfolder.
-   `$except` _(optional)_: Also optional. Here you can define which files you want to ignore within the root folder. If no value is passed, it will import all files.

---

### 🧪 Usage Examples

#### ✔ Import all files from the root folder

```php
RouteHelper::importRoutesFromFolder('admin');
```

Loads:

```
routes/admin/*.php
```

---

#### ✔ Import files from a subfolder

```php
RouteHelper::importRoutesFromFolder('admin', 'v2');
```

Loads:

```
routes/admin/v2/*.php
```

---

#### ✔ Import files from multiple subfolders (hierarchy)

```php
RouteHelper::importRoutesFromFolder('admin', ['ecommerce', 'v2']);
```

Loads:

```
routes/admin/ecommerce/v2/*.php
```

---

#### ✔ Import everything, except some files

```php
RouteHelper::importRoutesFromFolder('admin', null, ['auth', 'debug']);
```

Ignores:

```
routes/admin/auth.php
routes/admin/debug.php
```

---

#### ✔ Import from subfolders and ignore specific files

```php
RouteHelper::importRoutesFromFolder('admin', ['ecommerce', 'v2'], 'experimental');
```

Ignores:

```
routes/admin/ecommerce/v2/experimental.php
```

---

### `listAllRoutes()`

Returns a list of all routes registered in the application.

### Example:

```php
$routes = RouteHelper::listAllRoutes();
```

Return:

```php
[
    [
        'uri' => 'admin/users',
        'name' => 'admin.users.index',
        'method' => 'GET|HEAD',
        'action' => 'App\Http\Controllers\Admin\UserController@index',
    ],
    ...
]
```

Useful for:

-   debugging
-   documentation generation
-   route inspection in development environment
