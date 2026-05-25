<div class="notifications-ui-header">
    <div class="indicator">
        @if ($unreadCount > 0)
            <span class="indicator-item badge badge-xs rounded-full badge-primary">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif

        <div class="dropdown dropdown-bottom dropdown-end tooltip" data-tip="{{ __('ui.notifications') }}">
            <div tabindex="0" role="button" class="button button-item notifications-ui-header-button"
                aria-label="{{ __('components/notifications-ui.trigger_label') }}">
                <i class="fa-regular fa-bell"></i>
            </div>

            <section tabindex="-1" class="dropdown-content notifications-ui-header-dropdown"
                aria-labelledby="notifications-ui-header-title-{{ $this->id() }}">
                <header class="notifications-ui-dropdown-header">
                    <strong id="notifications-ui-header-title-{{ $this->id() }}">
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
                            wire:key="notifications-ui-header-{{ $notification['id'] }}" />
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
    </div>
</div>
