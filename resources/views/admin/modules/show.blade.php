@extends('layouts.admin')
@section('titulo', $module['name'])
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
                    <a href="{{ route('modules.index') }}">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        {{ __('ui.modules') }}
                    </a>
                </li>
                <li>
                    <span>
                        <i class="{{ $module['icon'] }}"></i>
                        {{ $module['name'] }}
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('conteudo')
    <section class="demo-docs-detail">
        <div class="demo-docs-detail-intro">
            <div class="demo-docs-detail-title">
                <span class="demo-docs-card-icon">
                    <i class="{{ $module['icon'] }}"></i>
                </span>
                <div>
                    <h2>{{ $module['name'] }}</h2>
                    <p>{{ $module['description'] }}</p>
                </div>
            </div>
            <a class="btn btn-soft btn-primary" href="{{ route('modules.index') }}">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('pages/modules.actions.back') }}
            </a>
        </div>

        @if ($module['slug'] === 'search-engine')
            <article class="demo-docs-panel demo-docs-panel-wide">
                <div class="dashboard-panel-header">
                    <div>
                        <h2>{{ __('components/search-engine.livewire.demo_title') }}</h2>
                        <p>{{ __('components/search-engine.livewire.demo_description') }}</p>
                    </div>
                </div>

                <div class="search-engine-livewire-demo">
                    <livewire:admin.search.demo-products-table />
                    <livewire:admin.search.demo-posts-table />
                </div>
            </article>
        @endif

        @if ($module['sections'] !== [])
            <div class="demo-docs-layout">
                <aside class="demo-docs-nav" aria-label="{{ $module['name'] }}">
                    <strong>{{ __('pages/modules.sections.documentation.title') }}</strong>
                    <nav>
                        @foreach ($module['sections'] as $section)
                            <a href="#{{ $section['id'] }}">{{ $section['title'] }}</a>

                            @if (($section['items'] ?? []) !== [])
                                <div>
                                    @foreach ($section['items'] as $item)
                                        <a href="#{{ $item['id'] }}">{{ $item['title'] }}</a>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </nav>
                </aside>

                <div class="demo-docs-section-list">
                    @foreach ($module['sections'] as $section)
                        <article class="demo-docs-panel demo-docs-panel-wide demo-docs-section" id="{{ $section['id'] }}">
                            <div class="dashboard-panel-header">
                                <div>
                                    <h2>{{ $section['title'] }}</h2>
                                    <p>{{ $section['description'] }}</p>
                                </div>
                            </div>

                            @if (($section['items'] ?? []) !== [])
                                <div class="demo-docs-topic-list">
                                    @foreach ($section['items'] as $item)
                                        <section class="demo-docs-topic" id="{{ $item['id'] }}">
                                            <div class="demo-docs-topic-copy">
                                                <h3>{{ $item['title'] }}</h3>
                                                <p>{{ $item['description'] }}</p>
                                            </div>

                                            @if (($item['bullets'] ?? []) !== [])
                                                <ul>
                                                    @foreach ($item['bullets'] as $bullet)
                                                        <li>{{ $bullet }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                            @if (filled($item['code'] ?? null))
                                                <div class="mockup-code w-full">
                                                    @foreach (preg_split('/\R/', rtrim($item['code'])) as $line)
                                                        <pre data-prefix="{{ $loop->iteration }}"><code>{{ $line }}</code></pre>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </section>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        @else
            <div class="demo-docs-detail-grid">
            <article class="demo-docs-panel demo-docs-panel-wide">
                <div class="dashboard-panel-header">
                    <div>
                        <h2>{{ __('pages/modules.sections.summary.title') }}</h2>
                        <p>{{ __('pages/modules.sections.summary.description') }}</p>
                    </div>
                    <span class="demo-docs-card-badge">{{ $module['status_label'] }}</span>
                </div>

                <div class="demo-docs-explanation-list">
                    @foreach ($module['summary'] as $item)
                        <p>{{ $item }}</p>
                    @endforeach
                </div>
            </article>

            <article class="demo-docs-panel demo-docs-panel-wide">
                <div class="dashboard-panel-header">
                    <div>
                        <h2>{{ __('pages/modules.sections.variations.title') }}</h2>
                        <p>{{ __('pages/modules.sections.variations.description') }}</p>
                    </div>
                </div>

                <div class="demo-docs-variation-grid">
                    @foreach ($module['variations'] as $variation)
                        <article class="demo-docs-variation-card">
                            @if ($module['slug'] === 'image-preview')
                                <div class="demo-docs-variation-preview">
                                    @if ($variation['mode'] === 'edit')
                                        <livewire:global.image-preview
                                            mode="edit"
                                            name="demo_edit_image"
                                            size="col-span-12"
                                            :existing="true"
                                            path="avatars/default-avatar.jpg"
                                            disk="public"
                                            placeholder="img/placeholders/avatars/default-avatar.jpg"
                                            :show-save-button="false" />
                                    @else
                                        <livewire:global.image-preview
                                            mode="create"
                                            name="demo_create_image"
                                            size="col-span-12"
                                            :show-save-button="false" />
                                    @endif
                                </div>
                            @endif

                            @if ($module['slug'] === 'notifications-ui')
                                <div class="demo-docs-variation-preview">
                                    <livewire:global.notifications-ui
                                        :scenario="$variation['mode']"
                                        :key="'notifications-ui-' . $variation['mode']" />
                                </div>
                            @endif

                            <div class="demo-docs-variation-copy">
                                <h3>{{ $variation['title'] }}</h3>
                                <p>{{ $variation['description'] }}</p>
                                <ul>
                                    @foreach ($variation['behaviors'] as $behavior)
                                        <li>{{ $behavior }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </article>
                    @endforeach
                </div>
            </article>

            <article class="demo-docs-panel demo-docs-panel-wide">
                <div class="dashboard-panel-header">
                    <div>
                        <h2>{{ __('pages/modules.sections.implementation.title') }}</h2>
                        <p>{{ __('pages/modules.sections.implementation.description') }}</p>
                    </div>
                </div>

                <div class="demo-docs-code-grid">
                    @foreach ($module['implementation'] as $example)
                        <div>
                            <h3>{{ $example['title'] }}</h3>
                            <div class="mockup-code w-full">
                                @foreach (preg_split('/\R/', rtrim($example['code'])) as $line)
                                    <pre data-prefix="{{ $loop->iteration }}"><code>{{ $line }}</code></pre>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            @if ($module['methods'] !== [])
                <article class="demo-docs-panel demo-docs-panel-wide">
                    <div class="dashboard-panel-header">
                        <div>
                            <h2>{{ __('pages/modules.sections.methods.title') }}</h2>
                            <p>{{ __('pages/modules.sections.methods.description') }}</p>
                        </div>
                    </div>

                    <div class="demo-docs-config-list">
                        @foreach ($module['methods'] as $method)
                            <div>
                                <dt>
                                    <code>{{ $method['name'] }}</code>
                                    <small>{{ $method['returns'] }}</small>
                                </dt>
                                <dd>{{ $method['description'] }}</dd>

                                @if (filled($method['example'] ?? null))
                                    <div class="mockup-code w-full mt-3">
                                        @foreach (preg_split('/\R/', rtrim($method['example'])) as $line)
                                            <pre data-prefix="{{ $loop->iteration }}"><code>{{ $line }}</code></pre>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </article>
            @endif

            <article class="demo-docs-panel">
                <div class="dashboard-panel-header">
                    <div>
                        <h2>{{ __('pages/modules.sections.configuration.title') }}</h2>
                        <p>{{ __('pages/modules.sections.configuration.description') }}</p>
                    </div>
                </div>

                <div class="demo-docs-config-list">
                    @foreach ($module['configuration'] as $config)
                        <div>
                            <dt>
                                <code>{{ $config['name'] }}</code>
                                <small>{{ $config['type'] }}</small>
                                <small>{{ $config['default'] }}</small>
                            </dt>
                            <dd>{{ $config['description'] }}</dd>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="demo-docs-panel">
                <div class="dashboard-panel-header">
                    <div>
                        <h2>{{ __('pages/modules.sections.improvements.title') }}</h2>
                        <p>{{ __('pages/modules.sections.improvements.description') }}</p>
                    </div>
                </div>

                <div class="demo-docs-explanation-list">
                    @foreach ($module['improvements'] as $item)
                        <p>{{ $item }}</p>
                    @endforeach
                </div>
            </article>
            </div>
        @endif
    </section>
@endsection
