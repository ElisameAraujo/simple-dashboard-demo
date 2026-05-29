<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', __('components/search-engine.web.preview_title'))</title>
    @include('components.global.theme-loader')
    @vite(['resources/css/admin/admin.css', 'resources/js/admin/admin.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-base-200 text-base-content">
    <x-web.navbar />

    <main class="web-preview-main">
        @yield('conteudo')
    </main>

    @livewireScripts
</body>

</html>
