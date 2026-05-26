@php
    $currentLocale = app()->getLocale();
    $currentLabel = config('app.supported_locales')[$currentLocale] ?? $currentLocale;
    $localeParts = explode('_', $currentLocale);
    $currentShortLabel = strtoupper($localeParts[1] ?? $localeParts[0]);
@endphp

<div class="mobile-action-shell mobile-action-language">
    <button type="button" class="mobile-action-button"
        x-bind:class="{ 'mobile-action-button-active': activePanel === 'language' }"
        x-on:click="activePanel = activePanel === 'language' ? null : 'language'"
        x-bind:aria-expanded="activePanel === 'language' ? 'true' : 'false'"
        aria-controls="mobile-language-panel">
        <i class="fa-solid fa-language"></i>
        <span>{{ $currentShortLabel }}</span>
    </button>

    <section id="mobile-language-panel" class="mobile-actions-panel" x-cloak
        x-show="activePanel === 'language'" x-transition>
        <span class="sr-only">{{ $currentLabel }}</span>
        <ul class="mobile-actions-list">
            @foreach (config('app.supported_locales') as $locale => $label)
                <li>
                    <a class="{{ app()->getLocale() === $locale ? 'active' : '' }}"
                        href="{{ route('locale.switch', $locale) }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
</div>
