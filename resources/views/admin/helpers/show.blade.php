@extends('layouts.admin')
@section('titulo', $helper['name'])
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
                    <a href="{{ route('helpers.index') }}">
                        <i class="fa-solid fa-circle-question"></i>
                        {{ __('ui.helpers') }}
                    </a>
                </li>
                <li>
                    <span>
                        <i class="{{ $helper['icon'] }}"></i>
                        {{ $helper['name'] }}
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
                    <i class="{{ $helper['icon'] }}"></i>
                </span>
                <div>
                    <h2>{{ $helper['name'] }}</h2>
                    <p>{{ $helper['description'] }}</p>
                </div>
            </div>
            <a class="demo-docs-button" href="{{ route('helpers.index') }}">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('pages/helpers.actions.back') }}
            </a>
        </div>

        <div class="demo-docs-detail-grid">
            <article class="demo-docs-panel demo-docs-panel-wide">
                <div class="dashboard-panel-header">
                    <div>
                        <h2>{{ __('pages/helpers.sections.how_it_works.title') }}</h2>
                        <p>{{ __('pages/helpers.sections.how_it_works.description') }}</p>
                    </div>
                </div>

                <div class="demo-docs-explanation-list">
                    @foreach ($helper['works'] as $item)
                        <p>{{ $item }}</p>
                    @endforeach
                </div>
            </article>

            <article class="demo-docs-panel demo-docs-panel-wide">
                <div class="dashboard-panel-header">
                    <div>
                        <h2>{{ __('pages/helpers.sections.methods.title') }}</h2>
                        <p>{{ __('pages/helpers.sections.methods.description') }}</p>
                    </div>
                </div>

                <div class="demo-docs-method-list">
                    @foreach ($helper['methods'] as $method)
                        <article class="demo-docs-method-card" id="{{ $method['name'] }}">
                            <div class="demo-docs-method-card-header">
                                <div>
                                    <h3>{{ $method['name'] }}</h3>
                                    <code>{{ $helper['alias'] }}::{{ $method['signature'] }}</code>
                                </div>
                                <span>{{ __('pages/helpers.methods.return') }}:
                                    <code>{{ $method['return'] }}</code></span>
                            </div>

                            <p>{{ $method['summary'] }}</p>

                            <div class="demo-docs-method-grid">
                                <div class="demo-docs-params">
                                    <h4>{{ __('pages/helpers.methods.parameters') }}</h4>
                                    @if (count($method['parameters']) === 0)
                                        <p>{{ __('pages/helpers.methods.no_parameters') }}</p>
                                    @else
                                        <dl>
                                            @foreach ($method['parameters'] as $parameter)
                                                <div>
                                                    <dt>
                                                        <code>{{ $parameter['name'] }}</code>
                                                        @if ($parameter['default'] !== null)
                                                            <small>
                                                                {{ $parameter['type'] }} =
                                                                {{ $parameter['default'] }}
                                                            </small>
                                                        @else
                                                            <small>{{ $parameter['type'] }}</small>
                                                        @endif
                                                    </dt>
                                                    <dd>{{ $parameter['description'] }}</dd>
                                                </div>
                                            @endforeach
                                        </dl>
                                    @endif
                                </div>

                                <div class="demo-docs-code-example">
                                    <h4>{{ __('pages/helpers.methods.example') }}</h4>
                                    <div class="mockup-code w-full">
                                        @foreach ($method['example']['usage'] as $line)
                                            <pre data-prefix="{{ $loop->iteration }}"><code>{{ $line }}</code></pre>
                                        @endforeach
                                        @foreach ($method['example']['output'] as $line)
                                            <pre data-prefix="//" class="text-success"><code>{{ $line }}</code></pre>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </article>
        </div>
    </section>
@endsection
