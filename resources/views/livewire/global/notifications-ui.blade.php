@if ($variant === 'header')
    @include('livewire.global.partials.notifications-ui-header')
@else
<div class="notifications-ui-demo">
    <div class="notifications-ui-shell {{ $scenario === 'empty' ? 'notifications-ui-shell-empty' : '' }}">
        <div class="notifications-ui-trigger-row">
            <div class="notifications-ui-trigger" role="button" tabindex="0"
                aria-label="{{ __('components/notifications-ui.trigger_label') }}">
                @if ($unreadCount > 0)
                    <span class="notifications-ui-count" aria-label="{{ trans_choice('components/notifications-ui.unread_count', $unreadCount, ['count' => $unreadCount]) }}">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
                <i class="fa-regular fa-bell"></i>
            </div>

            @if ($openedNotificationTitle)
                <p class="notifications-ui-opened">
                    {{ __('components/notifications-ui.opened', ['title' => $openedNotificationTitle]) }}
                </p>
            @endif
        </div>

        <section class="notifications-ui-dropdown" aria-labelledby="notifications-ui-dropdown-title-{{ $this->id() }}">
            <header class="notifications-ui-dropdown-header">
                <strong id="notifications-ui-dropdown-title-{{ $this->id() }}">
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
                        wire:key="notifications-ui-dropdown-{{ $notification['id'] }}" />
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

    @if ($scenario === 'modal')
        <section class="notifications-ui-modal notifications-ui-modal-embedded"
            aria-labelledby="notifications-ui-modal-title-{{ $this->id() }}">
            @include('livewire.global.partials.notifications-ui-modal', [
                'embedded' => true,
                'modalNotifications' => $modalNotifications,
                'unreadCount' => $unreadCount,
            ])
        </section>
    @endif

</div>
@endif
