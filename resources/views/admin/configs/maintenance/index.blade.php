@extends('layouts.admin')
@section('titulo', __('components/maintenance-mode.title'))

@section('page-header')
    <div class="page-header">
        <h1>@yield('titulo')</h1>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa-regular fa-house"></i>{{ __('ui.dashboard') }}
                    </a>
                </li>

                <li>
                    <span>
                        <i class="fa-solid fa-gear"></i>{{ __('components/maintenance-mode.breadcrumbs.settings') }}
                    </span>
                </li>

                <li>
                    <span>
                        <i class="fa-solid fa-wrench"></i>@yield('titulo')
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('conteudo')
    @livewire('admin.configs.maintenance-manager')
@endsection
