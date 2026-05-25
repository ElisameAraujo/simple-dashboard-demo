@extends('layouts.admin')
@section('titulo', __('pages/modules.index.title'))
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
                        <i class="fa-solid fa-boxes-stacked"></i>
                        {{ __('ui.modules') }}
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('conteudo')
    <section class="demo-docs-overview">
        <div class="demo-docs-intro">
            <span class="dashboard-kicker">{{ __('pages/modules.index.kicker') }}</span>
            <h2>{{ __('pages/modules.index.heading') }}</h2>
            <p>{{ __('pages/modules.index.description') }}</p>
        </div>

        <div class="demo-docs-grid">
            @foreach ($modules as $module)
                <a class="demo-docs-card" href="{{ $module['url'] }}">
                    <span class="demo-docs-card-icon">
                        <i class="{{ $module['icon'] }}"></i>
                    </span>
                    <span class="demo-docs-card-copy">
                        <strong>{{ $module['name'] }}</strong>
                        <small>{{ $module['description'] }}</small>
                    </span>
                    <span class="demo-docs-card-badge">{{ $module['status_label'] }}</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            @endforeach
        </div>
    </section>
@endsection
