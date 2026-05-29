<div class="web-search-wrapper">
    <button
        type="button"
        class="btn btn-square btn-ghost web-search-trigger"
        wire:click="toggle"
        aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
        aria-label="{{ __('components/search-engine.web.search_button') }}"
    >
        <i class="fa-solid fa-magnifying-glass"></i>
    </button>

    @if ($isOpen)
        <div class="web-search-dropdown" wire:click.stop>
            <div class="web-search-header">
                <strong>{{ __('components/search-engine.web.search_title') }}</strong>
                <button type="button" wire:click="close" aria-label="{{ __('components/search-engine.spotlight.close') }}">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('web.search') }}" method="GET" class="web-search-form">
                <label for="web-search-dropdown-input">{{ __('components/search-engine.web.search_label') }}</label>
                <div class="web-search-input-wrap" wire:ignore>
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        id="web-search-dropdown-input"
                        type="search"
                        name="q"
                        wire:model.live.debounce.300ms="term"
                        value="{{ $term }}"
                        placeholder="{{ __('components/search-engine.web.placeholder') }}"
                        autocomplete="off"
                    >
                </div>
            </form>

            <div class="web-search-results">
                @if ($term !== '' && mb_strlen($term) < $minimumLength)
                    <div class="web-search-empty">
                        <i class="fa-solid fa-keyboard"></i>
                        <span>{{ __('components/search-engine.spotlight.minimum_chars', ['count' => $minimumLength]) }}</span>
                    </div>
                @else
                    <div class="web-search-results-title">
                        {{ $term === '' ? __('components/search-engine.web.suggestions') : __('components/search-engine.web.best_results') }}
                    </div>

                    @forelse ($results as $result)
                        <x-web.search.result-item
                            :result="$result"
                            compact
                            wire:key="web-search-dropdown-{{ $result->key }}"
                        />
                    @empty
                        <div class="web-search-empty">
                            <i class="fa-regular fa-face-meh"></i>
                            <span>{{ __('components/search-engine.spotlight.empty') }}</span>
                        </div>
                    @endforelse
                @endif
            </div>

            <div class="web-search-footer">
                <a href="{{ route('web.search', ['q' => $term]) }}">
                    {{ __('components/search-engine.web.all_results') }}
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    @endif
</div>
