@props(['result', 'compact' => false])

<a href="{{ $result->url }}" class="web-search-result {{ $compact ? 'web-search-result-compact' : '' }}">
    <figure class="web-search-result-image">
        @if ($result->image)
            <img src="{{ $result->image }}?auto=format&fit=crop&w=240&q=75" alt="{{ $result->title }}">
        @else
            <span><i class="{{ $result->icon ?? 'fa-solid fa-magnifying-glass' }}"></i></span>
        @endif
    </figure>

    <div class="web-search-result-content">
        @if ($result->badge)
            <span class="web-search-badge web-search-badge-{{ $result->type }}">
                {{ $result->badge }}
            </span>
        @endif

        <h3>{{ $result->title }}</h3>

        @if ($result->summary)
            <p>{{ $result->summary }}</p>
        @endif

        <div class="web-search-result-meta">
            <span>{{ $result->groupLabel }}</span>
            <span>{{ __('components/search-engine.web.open_result') }}</span>
        </div>
    </div>
</a>
