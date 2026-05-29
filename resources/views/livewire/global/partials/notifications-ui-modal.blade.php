@php
    $modalTitleId = 'notifications-ui-modal-title-' . $this->id();
@endphp

<header class="notifications-ui-modal-header">
    <div>
        <h2 id="{{ $modalTitleId }}">{{ __('components/notifications-ui.modal.title') }}</h2>
        <p>{{ __('components/notifications-ui.modal.description') }}</p>
    </div>

    @unless ($embedded)
        <button type="button" class="notifications-ui-modal-close" wire:click="closeModal"
            aria-label="{{ __('components/notifications-ui.actions.close') }}">
            <i class="fa-solid fa-xmark"></i>
        </button>
    @endunless
</header>

<div class="notifications-ui-toolbar">
    <div class="notifications-ui-filters" role="group"
        aria-label="{{ __('components/notifications-ui.filters.label') }}">
        <button type="button" class="btn btn-sm {{ $filter === 'unread' ? 'btn-success' : 'btn-ghost' }}"
            wire:click="setFilter('unread')">
            {{ __('components/notifications-ui.filters.unread') }}
        </button>
        <button type="button" class="btn btn-sm {{ $filter === 'read' ? 'btn-success' : 'btn-ghost' }}"
            wire:click="setFilter('read')">
            {{ __('components/notifications-ui.filters.read') }}
        </button>
        <button type="button" class="btn btn-sm {{ $filter === 'all' ? 'btn-success' : 'btn-ghost' }}"
            wire:click="setFilter('all')">
            {{ __('components/notifications-ui.filters.all') }}
        </button>
    </div>

    <div class="notifications-ui-modal-actions">
        <button type="button" class="btn btn-sm btn-success" wire:click="markAllAsRead" @disabled($unreadCount === 0)>
            {{ __('components/notifications-ui.actions.mark_all_read') }}
        </button>
        <button type="button" class="btn btn-sm btn-error" wire:click="deleteRead">
            {{ __('components/notifications-ui.actions.delete_read') }}
        </button>
    </div>
</div>

<div class="notifications-ui-modal-list">
    @forelse ($modalNotifications as $notification)
        <x-admin.notifications-ui.item :notification="$notification" context="modal"
            wire:key="notifications-ui-modal-{{ $notification['id'] }}" />
    @empty
        <div class="notifications-ui-empty notifications-ui-empty-modal">
            <i class="fa-regular fa-bell-slash"></i>
            <span>{{ __('components/notifications-ui.empty.modal') }}</span>
        </div>
    @endforelse
</div>

<footer class="notifications-ui-modal-footer">
    <span>{{ __('components/notifications-ui.modal.footer') }}</span>
</footer>
