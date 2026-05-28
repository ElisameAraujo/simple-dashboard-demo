@extends('layouts.admin')
@section('titulo', $title)

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
                    <a href="{{ route('modules.show', ['module' => 'search-engine']) }}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Search Engine
                    </a>
                </li>
                <li>
                    <span>
                        <i class="fa-regular fa-pen-to-square"></i>
                        {{ $type }}
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('conteudo')
    <section class="demo-docs-detail">
        <div class="demo-docs-detail-grid">
            <article class="demo-docs-panel demo-docs-panel-wide">
                <div class="dashboard-panel-header">
                    <div>
                        <h2>{{ $title }}</h2>
                        <p>{{ $description }}</p>
                    </div>
                    <span class="demo-docs-card-badge">ID: {{ $identifier }}</span>
                </div>

                <div class="demo-docs-explanation-list">
                    <p>{{ __('components/search-engine.demo_edit.note') }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a class="btn btn-primary" href="{{ route('modules.show', ['module' => 'search-engine']) }}">
                        <i class="fa-solid fa-arrow-left"></i>
                        {{ __('components/search-engine.demo_edit.back') }}
                    </a>
                </div>
            </article>
        </div>
    </section>
@endsection
