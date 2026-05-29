@extends('layouts.admin')
@section('titulo', __('ui.dashboard'))
@section('page-header')
    <div class="page-header">
        <h1>@yield('titulo')</h1>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <span>
                        <i class="fa-regular fa-house"></i>{{ __('ui.dashboard') }}
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('conteudo')
    <section class="dashboard-overview">
        <div class="dashboard-intro">
            <div>
                <span class="dashboard-kicker">{{ __('pages/dashboard.kicker') }}</span>
                <h2>{{ __('pages/dashboard.intro.title') }}</h2>
                <p>{{ __('pages/dashboard.intro.description') }}</p>
            </div>
            <div class="dashboard-actions">
                <a class="btn btn-primary" href="https://github.com/ElisameAraujo" target="_blank" rel="noopener noreferrer">
                    <i class="fa-solid fa-user"></i>
                    {{ __('pages/dashboard.actions.profile') }}
                </a>
                <a class="btn btn-soft btn-secondary" href="https://github.com/ElisameAraujo/simple-dashboard"
                    target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-github"></i>
                    {{ __('ui.repository') }}
                </a>
            </div>
        </div>

        <div class="dashboard-summary-grid">
            @foreach ($summaryItems as $item)
                <article class="dashboard-summary-card">
                    <div class="dashboard-summary-icon dashboard-summary-icon-{{ $item['tone'] }}">
                        <i class="{{ $item['icon'] }}"></i>
                    </div>
                    <div>
                        <span>{{ $item['label'] }}</span>
                        <strong>{{ $item['value'] }}</strong>
                        <p>{{ $item['description'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="dashboard-content-grid">
        <div class="dashboard-panel dashboard-panel-wide">
            <div class="dashboard-panel-header">
                <div>
                    <h2>{{ __('pages/dashboard.sections.available_pages.title') }}</h2>
                    <p>{{ __('pages/dashboard.sections.available_pages.description') }}</p>
                </div>
            </div>

            <div class="dashboard-page-list">
                @foreach ($demoPages as $page)
                    <a class="dashboard-page-link" href="{{ route($page['route']) }}">
                        <span class="dashboard-page-icon">
                            <i class="{{ $page['icon'] }}"></i>
                        </span>
                        <span class="dashboard-page-copy">
                            <strong>{{ $page['title'] }}</strong>
                            <small>{{ $page['description'] }}</small>
                        </span>
                        <span class="dashboard-status">{{ $page['status'] }}</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="dashboard-panel">
            <div class="dashboard-panel-header">
                <div>
                    <h2>{{ __('pages/dashboard.sections.next_steps.title') }}</h2>
                    <p>{{ __('pages/dashboard.sections.next_steps.description') }}</p>
                </div>
            </div>

            <ol class="dashboard-step-list">
                @foreach ($nextSteps as $step)
                    <li>
                        <span>{{ $loop->iteration }}</span>
                        <p>{{ $step }}</p>
                    </li>
                @endforeach
            </ol>
        </div>

        <div class="dashboard-panel">
            <div class="dashboard-panel-header">
                <div>
                    <h2>{{ __('pages/dashboard.sections.useful_links.title') }}</h2>
                    <p>{{ __('pages/dashboard.sections.useful_links.description') }}</p>
                </div>
            </div>

            <div class="dashboard-link-list">
                @foreach ($usefulLinks as $link)
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer">
                        <span>
                            <i class="{{ $link['icon'] }}"></i>
                        </span>
                        <span>
                            <strong>{{ $link['label'] }}</strong>
                            <small>{{ $link['description'] }}</small>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="dashboard-panel dashboard-panel-wide">
            <div class="dashboard-panel-header">
                <div>
                    <h2>{{ __('pages/dashboard.sections.helper_docs.title') }}</h2>
                    <p>{{ __('pages/dashboard.sections.helper_docs.description') }}</p>
                </div>
            </div>

            <div class="dashboard-helper-grid">
                @foreach ($helperDocs as $helper)
                    <a href="{{ $helper['url'] }}">
                        <span>
                            <i class="{{ $helper['icon'] }}"></i>
                        </span>
                        <span>
                            <strong>{{ $helper['name'] }}</strong>
                            <small>{{ $helper['description'] }}</small>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

@endsection
