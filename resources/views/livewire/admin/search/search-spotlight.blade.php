<div>
    <div
        x-data="{
            isOpen: @entangle('isOpen').live,
            term: @entangle('term').live,
            openSearch() {
                this.isOpen = true;

                this.$nextTick(() => {
                    requestAnimationFrame(() => this.$refs.searchInput?.focus());
                });
            },
            closeSearch() {
                this.term = '';
                this.isOpen = false;
            },
            init() {
                window.addEventListener('keydown', (event) => {
                    if (!(event.ctrlKey || event.metaKey) || event.key?.toLowerCase() !== 'k') {
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();

                    this.openSearch();
                }, true);
            },
        }"
        x-cloak
        x-bind:class="{ 'admin-search-spotlight-open': isOpen }"
        x-bind:inert="! isOpen"
        x-bind:aria-hidden="(! isOpen).toString()"
        x-on:toggle-spotlight.window="openSearch()"
        x-on:keydown.escape.window="closeSearch()"
        class="admin-search-spotlight"
    >
        <button type="button" class="admin-search-backdrop" x-on:click="closeSearch()"
            aria-label="{{ __('components/search-engine.spotlight.close') }}"></button>

        <section
            class="admin-search-panel"
            role="dialog"
            aria-modal="true"
            aria-labelledby="admin-search-spotlight-title"
            x-show="isOpen"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        >
            <header class="admin-search-header" wire:ignore>
                <i class="fa-solid fa-magnifying-glass"></i>
                <input x-ref="searchInput" type="search" x-model.debounce.200ms="term"
                    placeholder="{{ __('components/search-engine.spotlight.placeholder') }}"
                    aria-label="{{ __('components/search-engine.spotlight.placeholder') }}" autocomplete="off">
            </header>

            @if (filled($term) && $groups->isNotEmpty())
                <div class="admin-search-filters"
                    aria-label="{{ __('components/search-engine.spotlight.group_filters') }}">
                    <button type="button" wire:click="selectGroup"
                        @class(['admin-search-filter', 'admin-search-filter-active' => blank($activeGroup)])>
                        <i class="fa-solid fa-layer-group"></i>
                        <span>{{ __('components/search-engine.spotlight.all_groups') }}</span>
                    </button>

                    @foreach ($groups as $group)
                        <button type="button" wire:click="selectGroup('{{ $group['key'] }}')"
                            @class(['admin-search-filter', 'admin-search-filter-active' => $group['active']])>
                            @if ($group['icon'])
                                <i class="{{ $group['icon'] }}"></i>
                            @endif
                            <span>{{ $group['label'] }}</span>
                            <small>{{ $group['count'] }}</small>
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="admin-search-summary">
                <strong id="admin-search-spotlight-title">
                    {{ filled($term) ? __('components/search-engine.spotlight.results') : __('components/search-engine.spotlight.suggestions') }}
                </strong>

                @if (filled($term) && mb_strlen(trim($term)) < $minimumLength)
                    <span>{{ __('components/search-engine.spotlight.minimum_chars', ['count' => $minimumLength]) }}</span>
                @else
                    <span>{{ trans_choice('components/search-engine.spotlight.count', $results->count(), ['count' => $results->count()]) }}</span>
                @endif
            </div>

            <div class="admin-search-results">
                @forelse ($results as $result)
                    <div
                        class="admin-search-result {{ $result->url ? 'admin-search-result-clickable' : '' }}"
                        wire:key="{{ $result->key }}"
                        @if ($result->url)
                            role="link"
                            tabindex="0"
                            x-on:click="window.location.href = @js($result->url)"
                            x-on:keydown.enter="window.location.href = @js($result->url)"
                        @endif
                    >
                        <span class="admin-search-icon">
                            @if ($result->image)
                                <img src="{{ $result->image }}" alt="">
                            @elseif ($result->icon)
                                <i class="{{ $result->icon }}"></i>
                            @else
                                <i class="fa-solid fa-arrow-right"></i>
                            @endif
                        </span>

                        <span class="admin-search-content">
                            <span class="admin-search-title-row">
                                <strong>{{ $result->title }}</strong>
                                @if ($result->badge)
                                    <small>{{ $result->badge }}</small>
                                @endif
                            </span>

                            @if ($result->summary)
                                <span>{{ $result->summary }}</span>
                            @endif
                        </span>

                        <span class="admin-search-side">
                            @if ($result->group)
                                <span class="admin-search-group">{{ $result->groupLabel }}</span>
                            @endif

                            @if ($result->actions !== [])
                                <span class="admin-search-actions">
                                    @foreach ($result->actions as $action)
                                        <a href="{{ $action['url'] }}" class="admin-search-action"
                                            title="{{ $action['label'] }}" aria-label="{{ $action['label'] }}"
                                            x-on:click.stop>
                                            @if ($action['icon'])
                                                <i class="{{ $action['icon'] }}"></i>
                                            @else
                                                <span>{{ $action['label'] }}</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </span>
                            @endif
                        </span>
                    </div>
                @empty
                    <div class="admin-search-empty">
                        <i class="fa-regular fa-face-meh"></i>
                        <span>{{ __('components/search-engine.spotlight.empty') }}</span>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
