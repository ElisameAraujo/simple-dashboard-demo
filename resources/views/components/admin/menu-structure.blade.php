<div class="dropdown profile">
    <div tabindex="0" role="button" class="profile-button">
        <div class="profile-pic">
            <img src="{{ asset('img/placeholders/avatars/default-avatar.jpg') }}" alt="">
        </div>
        <div class="profile-details">
            <span class="user-name">John Doe</span>
            <span class="user-email">john@doe.com</span>
        </div>
    </div>
    <ul tabindex="-1" class="dropdown-content">
        <li class="menu-item">
            <a href="{{ route('admin.account.my-profile') }}">
                <i class="fa-solid fa-user"></i> {{ __('ui.my_profile') }}
            </a>
        </li>

        <li>
            <a href="{{ route('admin.account.notifications') }}">
                <i class="fa-regular fa-bell"></i> {{ __('ui.notifications') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.account.security') }}">
                <i class="fa-solid fa-fingerprint"></i> {{ __('ui.security') }}
            </a>
        </li>
        <li>
            <a>
                <i class="fa-solid fa-arrow-right-from-bracket"></i> {{ __('ui.logout') }}
            </a>
        </li>

    </ul>
</div>

<button type="button" class="search-box" onclick="window.dispatchEvent(new CustomEvent('toggle-spotlight'))">
    <span class="search-input">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span>{{ __('ui.search') }}</span>
    </span>
    <kbd class="kbd kbd-sm px-2">Ctrl+K</kbd>
</button>

<div class="actions-buttons">
    <label class="swap tooltip" data-tip="{{ __('ui.switch_theme') }}">
        <input type="checkbox" data-toggle-theme="dark,light" data-act-class="ACTIVECLASS" />
        <div class="swap-on"><i class="fa-regular fa-sun"></i></div>
        <div class="swap-off"><i class="fa-regular fa-moon"></i></div>
    </label>

    <div class="dropdown dropdown-bottom dropdown-end tooltip" data-tip="{{ __('ui.notifications') }}">
        <div tabindex="0" role="button" class="button-item">
            <i class="fa-regular fa-bell"></i>
            <span class="indicator-item badge badge-xs rounded-sm badge-primary">2</span>
        </div>
        <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-92 p-2 shadow-sm">
            <li><a>Item 1</a></li>
            <li><a>Item 2</a></li>
        </ul>
    </div>
</div>

<nav class="side-menu">
    <h2 class="menu-section">{{ __('ui.demo') }}</h2>
    <ul>
        <li>
            <a class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-chart-line"></i>
                {{ __('ui.dashboard') }}
            </a>
        </li>
        <h2 class="menu-section">{{ __('ui.account') }}</h2>
        <li data-submenu-id="account-settings"
            class="{{ request()->routeIs('admin.account.*') ? 'open' : '' }}">
            <a href="#" class="has-submenu">
                <span>
                    <i class="fa-solid fa-user-gear"></i>
                    {{ __('ui.settings') }}
                </span>
                <i class="fa-solid fa-plus text-xs" id="submenu-icon"></i>
            </a>
            <ul>
                <li>
                    <a class="{{ request()->routeIs('admin.account.my-profile') ? 'active' : '' }}"
                        href="{{ route('admin.account.my-profile') }}">{{ __('ui.my_profile') }}</a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.account.notifications') ? 'active' : '' }}"
                        href="{{ route('admin.account.notifications') }}">{{ __('ui.notifications') }}</a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.account.security') ? 'active' : '' }}"
                        href="{{ route('admin.account.security') }}">{{ __('ui.security') }}</a>
                </li>
            </ul>
        </li>

        <h2 class="menu-section">{{ __('ui.project') }}</h2>
        <li>
            <a class="menu-item" href="{{ app()->getLocale() === 'pt_BR'
                ? 'https://github.com/ElisameAraujo/simple-dashboard/blob/main/README.pt-br.md'
                : 'https://github.com/ElisameAraujo/simple-dashboard' }}"
                target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-readme"></i>
                {{ __('ui.readme') }}
            </a>
        </li>
        <li>
            <a class="menu-item" href="{{ app()->getLocale() === 'pt_BR'
                ? 'https://github.com/ElisameAraujo/simple-dashboard/tree/main/documentation/pt-BR'
                : 'https://github.com/ElisameAraujo/simple-dashboard/tree/main/documentation/en' }}"
                target="_blank" rel="noopener noreferrer">
                <i class="fa-solid fa-book-open"></i>
                {{ __('ui.helpers') }}
            </a>
        </li>
        <li>
            <a class="menu-item" href="https://github.com/ElisameAraujo/simple-dashboard" target="_blank"
                rel="noopener noreferrer">
                <i class="fa-brands fa-github"></i>
                {{ __('ui.repository') }}
            </a>
        </li>
    </ul>
</nav>
