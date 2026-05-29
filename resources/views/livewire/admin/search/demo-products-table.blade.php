<article class="search-engine-table-panel">
    <div class="search-engine-table-header">
        <div>
            <h3>{{ __('components/search-engine.livewire.products.title') }}</h3>
            <p>{{ __('components/search-engine.livewire.products.description') }}</p>
        </div>
        <span>{{ trans_choice('components/search-engine.spotlight.count', $products->total(), ['count' => $products->total()]) }}</span>
    </div>

    <div class="search-engine-table-toolbar">
        <label>
            <span>{{ __('components/search-engine.livewire.search') }}</span>
            <input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="{{ __('components/search-engine.livewire.products.placeholder') }}"
            >
        </label>

        <label>
            <span>{{ __('components/search-engine.livewire.status') }}</span>
            <select wire:model.live="status">
                <option value="">{{ __('components/search-engine.livewire.all_statuses') }}</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </label>

        <label>
            <span>{{ __('components/search-engine.livewire.order_by') }}</span>
            <select wire:model.live="orderBy">
                @foreach ($orderFields as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </label>

        <label>
            <span>{{ __('components/search-engine.livewire.direction') }}</span>
            <select wire:model.live="orderDirection">
                <option value="desc">{{ __('components/search-engine.livewire.desc') }}</option>
                <option value="asc">{{ __('components/search-engine.livewire.asc') }}</option>
            </select>
        </label>

        <button type="button" wire:click="resetFilters">
            <i class="fa-solid fa-rotate-left"></i>
            {{ __('components/search-engine.livewire.reset') }}
        </button>
    </div>

    <div class="search-engine-table-scroll">
        <table class="search-engine-table">
            <thead>
                <tr>
                    <th>{{ __('components/search-engine.livewire.columns.item') }}</th>
                    <th>{{ __('components/search-engine.livewire.columns.status') }}</th>
                    <th>{{ __('components/search-engine.livewire.columns.price') }}</th>
                    <th>{{ __('components/search-engine.livewire.columns.published_at') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr wire:key="demo-product-{{ $product->id }}">
                        <td>
                            <div class="search-engine-table-item">
                                @if ($product->image)
                                    <img src="{{ $product->image }}&auto=format&fit=crop&w=96&q=70" alt="">
                                @else
                                    <span><i class="fa-solid fa-bag-shopping"></i></span>
                                @endif
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                    <small>{{ Str::limit($product->description, 92) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="search-engine-status search-engine-status-{{ $product->status }}">
                                {{ $statuses[$product->status] ?? $product->status }}
                            </span>
                        </td>
                        <td>{{ \Illuminate\Support\Number::currency((float) $product->price, 'BRL', app()->getLocale()) }}</td>
                        <td>{{ $product->published_at?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="search-engine-table-empty">
                            {{ __('components/search-engine.livewire.empty') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="search-engine-table-pagination">
        {{ $products->links() }}
    </div>
</article>
