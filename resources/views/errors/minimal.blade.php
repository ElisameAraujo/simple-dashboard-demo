<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') | @yield('title')</title>
    @include('components.global.theme-loader')
    @vite(['resources/css/admin/admin.css', 'resources/js/admin/admin.js'])
</head>

<body class="min-h-screen bg-base-100 text-base-content">
    <main class="mx-auto flex min-h-screen max-w-3xl flex-col items-center justify-center gap-6 px-6 py-12 text-center">
        <div class="max-w-xs">
            @yield('error-image')
        </div>

        <div class="flex flex-col gap-3">
            <span class="dashboard-kicker">@yield('code')</span>
            <h1 class="font-unbounded text-4xl font-bold uppercase">@yield('title')</h1>
            <p class="text-gray-400">@yield('message')</p>
        </div>
    </main>
</body>

</html>
