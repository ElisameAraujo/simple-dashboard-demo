@extends('layouts.admin')
@section('titulo', __('pages/helpers.index.title'))
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
                        <i class="fa-solid fa-circle-question"></i>
                        {{ __('ui.helpers') }}
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('conteudo')
    <section class="helpers-overview">
        <div class="helpers-intro">
            <span class="dashboard-kicker">{{ __('pages/helpers.index.kicker') }}</span>
            <h2>{{ __('pages/helpers.index.heading') }}</h2>
            <p>{{ __('pages/helpers.index.description') }}</p>
        </div>

        <div class="helpers-grid">
            @foreach ($helpers as $helper)
                <a class="helper-card" href="{{ $helper['url'] }}">
                    <span class="helper-card-icon">
                        <i class="{{ $helper['icon'] }}"></i>
                    </span>
                    <span class="helper-card-copy">
                        <strong>{{ $helper['name'] }}</strong>
                        <small>{{ $helper['description'] }}</small>
                    </span>
                    <span class="helper-card-count">
                        {{ trans_choice('pages/helpers.index.method_count', count($helper['methods']), ['count' => count($helper['methods'])]) }}
                    </span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            @endforeach
        </div>
    </section>
@endsection
