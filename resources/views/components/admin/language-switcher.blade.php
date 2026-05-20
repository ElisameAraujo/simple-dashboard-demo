@php
    $currentLocale = app()->getLocale();
    $currentLabel = config('app.supported_locales')[$currentLocale] ?? $currentLocale;
    $currentShortLabel = strtoupper(explode('_', $currentLocale)[0]);
@endphp

<div class="dropdown dropdown-bottom dropdown-end language-switcher tooltip {{ $class ?? '' }}"
    data-tip="{{ __('ui.language') }}">
    <div tabindex="0" role="button" class="button">
        <i class="fa-solid fa-language"></i>
        <span class="language-label-full">{{ $currentLabel }}</span>
        <span class="language-label-short">{{ $currentShortLabel }}</span>
    </div>
    <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-44 p-2 shadow-sm">
        @foreach (config('app.supported_locales') as $locale => $label)
            <li>
                <a class="{{ app()->getLocale() === $locale ? 'active' : '' }}"
                    href="{{ route('locale.switch', $locale) }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
