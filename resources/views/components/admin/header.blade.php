<div class="header">
    <div class="mobile-menu">
        <button id="open-mobile">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
    </div>

    <div class="mobile-header-actions">
        @include('components.admin.language-switcher', ['class' => 'language-switcher-mobile'])
    </div>

    @yield('page-header')

    <div class="header-buttons">
        @include('components.admin.language-switcher')

        <label class="swap tooltip" data-tip="{{ __('ui.switch_theme') }}">
            <input type="checkbox" data-toggle-theme="dark,light" data-act-class="ACTIVECLASS" />
            <div class="swap-on"><i class="fa-regular fa-sun"></i></div>
            <div class="swap-off"><i class="fa-regular fa-moon"></i></div>
        </label>

        <div class="indicator">
            <div class="dropdown dropdown-bottom dropdown-end tooltip" data-tip="{{ __('ui.notifications') }}">
                <div tabindex="0" role="button" class="button">
                    <span class="indicator-item badge badge-xs rounded-full badge-primary">2</span>
                    <i class="fa-regular fa-bell"></i>
                </div>
                <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                    <li><a>Item 1</a></li>
                    <li><a>Item 2</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
