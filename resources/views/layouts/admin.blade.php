<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administração | @yield('titulo')</title>

    @include('components.global.theme-loader')
    @vite(['resources/css/admin/admin.css', 'resources/js/admin/admin.js'])
    @livewireStyles
</head>

<body>
    <div class="app">
        @include('components.admin.side-menu')
        @include('components.admin.side-menu-mobile')
        <main>
            @include('components.admin.header')
            <div class="main-content">
                @yield('conteudo')
            </div>
        </main>
    </div>
    @livewire('livewire-ui-spotlight')

    @livewireScripts
</body>

</html>
