<header class="web-preview-navbar-shell">
    <div class="navbar web-preview-navbar">
        <div class="navbar-start">
            <a class="btn btn-ghost web-preview-brand" href="{{ route('web.preview') }}">
                <i class="fa-brands fa-laravel"></i>
                Simple Web
            </a>
        </div>

        <nav class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal gap-1 px-1">
                <li>
                    <a class="{{ request()->routeIs('web.preview') ? 'active' : '' }}" href="{{ route('web.preview') }}">
                        {{ __('components/search-engine.web.nav.home') }}
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('web.search') ? 'active' : '' }}" href="{{ route('web.search') }}">
                        {{ __('components/search-engine.web.nav.search') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('modules.search-engine.section', 'web-search') }}">
                        {{ __('components/search-engine.web.nav.docs') }}
                    </a>
                </li>
            </ul>
        </nav>

        <div class="navbar-end gap-2">
            <livewire:web.search.search-dropdown />

            <label class="btn btn-square btn-ghost swap swap-rotate" aria-label="{{ __('ui.switch_theme') }}">
                <input type="checkbox" data-toggle-theme="dark,light" data-act-class="ACTIVECLASS" />
                <i class="fa-regular fa-sun swap-on"></i>
                <i class="fa-regular fa-moon swap-off"></i>
            </label>
        </div>
    </div>
</header>
