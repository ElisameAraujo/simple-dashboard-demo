<div class="header">
    <div class="mobile-menu">
        <button id="open-mobile">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
    </div>

    @yield('page-header')

    <div class="header-buttons">
        <livewire:admin.configs.maintenance-header-status />

        @include('components.admin.language-switcher')

        <label class="swap tooltip" data-tip="{{ __('ui.switch_theme') }}">
            <input type="checkbox" data-toggle-theme="dark,light" data-act-class="ACTIVECLASS" />
            <div class="swap-on"><i class="fa-regular fa-sun"></i></div>
            <div class="swap-off"><i class="fa-regular fa-moon"></i></div>
        </label>

        <livewire:global.notifications-ui variant="header" />
    </div>
</div>
