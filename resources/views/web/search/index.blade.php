@extends('layouts.web')

@section('titulo', __('components/search-engine.web.page_title'))

@section('conteudo')
    <section class="web-search-page">
        <div class="web-search-page-header">
            <span>{{ __('components/search-engine.web.kicker') }}</span>
            <h1>{{ __('components/search-engine.web.page_title') }}</h1>
            <p>{{ __('components/search-engine.web.page_description') }}</p>

            <form action="{{ route('web.search') }}" method="GET" class="web-search-page-form">
                <div class="web-search-input-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        type="search"
                        name="q"
                        value="{{ $term }}"
                        placeholder="{{ __('components/search-engine.web.placeholder') }}"
                        autocomplete="off"
                    >
                </div>
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    {{ __('components/search-engine.web.submit') }}
                </button>
            </form>
        </div>

        <div class="web-search-page-summary">
            @if ($term === '')
                {{ __('components/search-engine.web.suggestions_summary') }}
            @else
                {!! __('components/search-engine.web.results_summary', [
                    'count' => $resultsCount,
                    'term' => e($term),
                ]) !!}
            @endif
        </div>

        <div class="web-search-page-results">
            @forelse ($results as $result)
                <x-web.search.result-item :result="$result" />
            @empty
                <div class="web-search-empty web-search-empty-page">
                    <i class="fa-regular fa-face-meh"></i>
                    <span>{{ __('components/search-engine.spotlight.empty') }}</span>
                </div>
            @endforelse
        </div>

        <div class="web-search-page-pagination">
            {{ $results->links() }}
        </div>
    </section>
@endsection
