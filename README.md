# Simple Dashboard Demo

This repository contains the working Simple Dashboard demo. It is used to show, test, and document the global helpers and extra modules available in the admin panel.

The demo is independent from the starter repository. If you want the clean project base, use [`simple-dashboard`](https://github.com/ElisameAraujo/simple-dashboard). If you want to explore the features in action, use this demo repository.

## Stack

- Laravel 13
- Livewire 4
- Tailwind CSS 4
- DaisyUI 5
- FontAwesome 7
- Vite 8
- SQLite for local development

## What Is Included

### Helpers

Helpers live in `app/Helpers` and are documented inside the demo UI.

| Helper               | Focus                                                               |
| -------------------- | ------------------------------------------------------------------- |
| `DateHelper`         | Dates, periods, and relative text.                                  |
| `DiskHelper`         | Upload, replacement, removal, and URL generation for Laravel disks. |
| `HTMLHelper`         | Fake HTML generation for demos, previews, and docs.                 |
| `MediaHelper`        | Media resolution, display, download, and MIME type helpers.         |
| `NotificationHelper` | Reading Laravel notifications for the authenticated user.           |
| `NumberHelper`       | Locale-aware numbers, currency, area, and ordinals.                 |
| `RouteHelper`        | Organized import of route files and folders.                        |
| `RuleHelper`         | Extracting values from Laravel validation rules.                    |
| `TextHelper`         | Cleaning, normalization, pluralization, slugs, and UI text.         |
| `UserHelper`         | Safe access to basic user data and optional permission extras.      |

### Modules

Modules live in the **Modules / Extras** area inside the panel.

| Module             | What it demonstrates                                                           |
| ------------------ | ------------------------------------------------------------------------------ |
| `ImagePreview`     | Decoupled image preview for Livewire create and edit forms.                    |
| `Visits`           | Standalone visit tracking and popularity scopes for Eloquent models.           |
| `Notifications UI` | Visual admin notification interface with mocked data.                          |
| `Maintenance Mode` | WordPress-style maintenance mode without taking down the admin panel.          |
| `Search Engine`    | Search engine for Spotlight, web search, models, statics, and Livewire tables. |
| `Rich Text Media`  | Upload, commit, and cleanup for images embedded in WYSIWYG editors.            |

## Installation

Clone the repository:

```bash
git clone https://github.com/ElisameAraujo/simple-dashboard-demo.git
cd simple-dashboard-demo
```

Install PHP and JavaScript dependencies:

```bash
composer install
npm install
```

Create `.env`, generate the app key, and prepare the database:

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
```

To rebuild the demonstration data:

```bash
composer demo:fresh
```

Build assets:

```bash
npm run build
```

## Running The Demo

Use the development script:

```bash
composer run dev
```

It starts the Laravel server, Vite, and the queue listener.

Then open:

```text
http://127.0.0.1:8000
```

## Manual Test Flow

### Panel

1. Open `/`.
2. Switch language from the header or mobile menu.
3. Toggle light and dark themes.
4. Open Spotlight with `Ctrl+K` or the search field.
5. Search terms such as `maintenance`, `visits`, `media`, `product`, or `post`.

### Helpers

1. Open `/helpers`.
2. Open each helper from the sidebar.
3. Check examples, methods, and YAML-generated documentation.

### Modules

1. Open `/modules`.
2. Open `ImagePreview` and test create/edit states.
3. Open `Notifications UI` and test dropdown, modal, and notification states.
4. Open `Maintenance Mode`, enable maintenance, and test `/site-preview`.
5. Open `Search Engine` and navigate through architecture, Spotlight, web, and Livewire sections.
6. Open `Rich Text Media` and review TinyMCE, CKEditor, Quill, Froala, Tiptap, and Lexical integration examples.

### Web Search

1. Open `/site-preview`.
2. Use the search dropdown in the navbar.
3. Open `/site-preview/search?q=media` to see the results page.

### Mobile

1. Reduce the browser width.
2. Open the mobile menu.
3. Test language, theme, notifications, and maintenance.
4. Check that dropdowns and modals are not trapped inside the sidebar.

## Validation Commands

Asset build:

```bash
npm run build
```

Module tests:

```bash
php artisan test tests/Feature/Modules
```

Search Engine tests:

```bash
php artisan test --filter=SearchEngineTest
```

Maintenance mode tests:

```bash
php artisan test --filter=MaintenanceModeTest
```

Helper documentation and localization tests:

```bash
php artisan test tests/Feature/Localization
```

## Internal Documentation

The UI documentation comes from YAML:

```text
resources/docs/helpers/{locale}
resources/docs/modules/{locale}
```

When a helper or module public contract changes, update both `en` and `pt_BR` files and run the matching documentation tests.

## Demo Versus Starter

[`simple-dashboard-demo`](https://github.com/ElisameAraujo/simple-dashboard-demo) contains live screens, fake data, visual examples, and behavior tests.

[`simple-dashboard`](https://github.com/ElisameAraujo/simple-dashboard) is the clean starter project for real use: core, reusable components, and implementation documentation, without fake data or unnecessary demonstration pages.

## Notes

- `wire-elements/modal` is used for Livewire modals that need state or validation.
- Simple confirmation modals use DaisyUI.
- Search Engine and Rich Text Media are project-configurable and do not force a final UI.
- The demo can contain more didactic code than the starter project because its job is to teach and validate the flows.
