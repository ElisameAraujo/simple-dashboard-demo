<div class="dropdown profile">
    <div tabindex="0" role="button" class="profile-button">
        <div class="profile-pic">
            <img src="{{ asset('img/placeholders/default-avatar.jpg') }}" alt="">
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
    <h2 class="menu-section">Seção 1</h2>
    <ul>
        <li>
            <a class="menu-item active" href="#">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a class="menu-item" href="#">
                <i class="fa-solid fa-building"></i>
                Accounts
            </a>
        </li>
        <li data-submenu-id="cards-1">
            <a href="#" class="has-submenu">
                <span>
                    <i class="fa-solid fa-credit-card"></i>
                    Cards
                </span>
                <i class="fa-solid fa-plus text-xs" id="submenu-icon"></i>
            </a>
            <ul>
                <li>
                    <a href="#">Subitem 1</a>
                </li>
                <li>
                    <a class="sub-item" href="#">Subitem 2</a>
                </li>
                <li>
                    <a class="sub-item" href="#">Subitem 3</a>
                </li>
            </ul>
        </li>
        <h2 class="menu-section">Seção 2</h2>
        <li>
            <a class="menu-item" href="#">
                <i class="fa-solid fa-users"></i>
                Payees
            </a>
        </li>
        <li>
            <a class="menu-item" href="#">
                <i class="fa-solid fa-file-invoice"></i>
                Invoices
            </a>
        </li>

        <h2 class="menu-section">Seção 2</h2>
        <li>
            <a class="menu-item" href="#">
                <i class="fa-solid fa-users"></i>
                Payees
            </a>
        </li>
        <li>
            <a class="menu-item" href="#">
                <i class="fa-solid fa-file-invoice"></i>
                Invoices
            </a>
        </li>

        <h2 class="menu-section">Seção 2</h2>
        <li>
            <a class="menu-item" href="#">
                <i class="fa-solid fa-users"></i>
                Payees
            </a>
        </li>
        <li>
            <a class="menu-item" href="#">
                <i class="fa-solid fa-file-invoice"></i>
                Invoices
            </a>
        </li>

        <h2 class="menu-section">Seção 2</h2>
        <li>
            <a class="menu-item" href="#">
                <i class="fa-solid fa-users"></i>
                Payees
            </a>
        </li>
        <li>
            <a class="menu-item" href="#">
                <i class="fa-solid fa-file-invoice"></i>
                Invoices
            </a>
        </li>

        <li data-submenu-id="cards-2">
            <a href="#" class="has-submenu">
                <span>
                    <i class="fa-solid fa-credit-card"></i>
                    Cards
                </span>
                <i class="fa-solid fa-plus text-xs" id="submenu-icon"></i>
            </a>
            <ul>
                <li>
                    <a href="#" class="active">Subitem 1</a>
                </li>
                <li>
                    <a class="sub-item" href="#">Subitem 2</a>
                </li>
                <li>
                    <a class="sub-item" href="#">Subitem 3</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
