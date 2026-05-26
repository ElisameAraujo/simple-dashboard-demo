<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('components/maintenance-mode.preview.title') }}</title>
    @include('components.global.theme-loader')
    @vite(['resources/css/admin/admin.css', 'resources/js/admin/admin.js'])
</head>

<body class="min-h-screen bg-base-100 text-base-content">
    <main class="mx-auto flex min-h-screen max-w-3xl flex-col justify-center gap-6 px-6 py-12">
        <span class="dashboard-kicker">{{ __('components/maintenance-mode.preview.kicker') }}</span>
        <div class="flex flex-col gap-3">
            <h1 class="font-unbounded text-4xl font-bold uppercase">
                {{ __('components/maintenance-mode.preview.heading') }}
            </h1>
            <p class="text-gray-500">
                {{ __('components/maintenance-mode.preview.description') }}
            </p>
        </div>

        <div class="rounded-lg border border-gray-200 bg-base-100 p-4 shadow-sm">
            <p class="text-sm text-gray-500">
                {{ __('components/maintenance-mode.preview.note') }}
            </p>
        </div>

        <a class="btn btn-primary w-fit" href="{{ route('dashboard') }}">
            <i class="fa-solid fa-arrow-left"></i>
            {{ __('components/maintenance-mode.preview.back') }}
        </a>
    </main>
</body>

</html>
