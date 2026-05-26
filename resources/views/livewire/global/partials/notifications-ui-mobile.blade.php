<div class="mobile-action-shell mobile-action-notifications notifications-ui-mobile">
    <button type="button" @class([
            'mobile-action-button notifications-ui-mobile-button',
            'mobile-action-button-wide' => ! $maintenanceShortcutEnabled,
        ])
        x-bind:class="{ 'mobile-action-button-active': activePanel === 'notifications' }"
        x-on:click="activePanel = activePanel === 'notifications' ? null : 'notifications'"
        x-bind:aria-expanded="activePanel === 'notifications' ? 'true' : 'false'"
        aria-controls="mobile-notifications-panel"
        aria-label="{{ __('components/notifications-ui.trigger_label') }}">
        @if ($unreadCount > 0)
            <span class="notifications-ui-count" aria-label="{{ trans_choice('components/notifications-ui.unread_count', $unreadCount, ['count' => $unreadCount]) }}">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif

        <i class="fa-regular fa-bell"></i>
    </button>

    <section id="mobile-notifications-panel"
        class="mobile-actions-panel notifications-ui-mobile-panel"
        aria-labelledby="mobile-notifications-title-{{ $this->id() }}"
        x-cloak x-show="activePanel === 'notifications'" x-transition>
        <header class="notifications-ui-dropdown-header">
            <strong id="mobile-notifications-title-{{ $this->id() }}">
                {{ __('components/notifications-ui.title') }}
            </strong>

            @if ($unreadCount > 0)
                <button type="button" wire:click="markAllAsRead">
                    {{ __('components/notifications-ui.actions.mark_all_read') }}
                </button>
            @endif
        </header>

        <div class="notifications-ui-list">
            @forelse ($dropdownNotifications as $notification)
                <x-admin.notifications-ui.item
                    :notification="$notification"
                    context="dropdown"
                    wire:key="notifications-ui-mobile-{{ $notification['id'] }}" />
            @empty
                <div class="notifications-ui-empty">
                    <i class="fa-regular fa-bell-slash"></i>
                    <span>{{ __('components/notifications-ui.empty.dropdown') }}</span>
                </div>
            @endforelse
        </div>

        <footer class="notifications-ui-dropdown-footer">
            <button type="button"
                wire:click="$dispatch('openModal', { component: 'global.notifications-ui-modal' })">
                {{ __('components/notifications-ui.actions.view_all') }}
            </button>
            <span>{{ __('components/notifications-ui.backend_free') }}</span>
        </footer>
    </section>
</div>
