@props([
    'notification',
    'context' => 'dropdown',
])

@php
    $isUnread = blank($notification['read_at'] ?? null);
    $title = $notification['title'] ?? __('components/notifications-ui.fallback.title');
    $description = $notification['description'] ?? $notification['content'] ?? '';
    $author = $notification['author'] ?? __('components/notifications-ui.fallback.author');
    $label = $notification['label'] ?? __('components/notifications-ui.fallback.label');
    $icon = $notification['icon'] ?? 'fa-regular fa-bell';
    $timeLabel = $notification['time_label'] ?? '';
@endphp

<article {{ $attributes->class([
    'notifications-ui-item',
    'notifications-ui-item-unread' => $isUnread,
    'notifications-ui-item-modal' => $context === 'modal',
]) }}>
    <button type="button" class="notifications-ui-main" wire:click="openNotification('{{ $notification['id'] }}')">
        <span class="notifications-ui-icon">
            <i class="{{ $icon }}"></i>
        </span>

        <span class="notifications-ui-body">
            <span class="notifications-ui-title-row">
                <span class="notifications-ui-title">{{ $title }}</span>
                @if ($context === 'modal')
                    <span class="notifications-ui-badge">{{ $label }}</span>
                @endif
            </span>
            <span class="notifications-ui-author">{{ $author }}</span>
            <span class="notifications-ui-content">{{ $description }}</span>
            @if (filled($timeLabel))
                <span class="notifications-ui-date">{{ $timeLabel }}</span>
            @endif
        </span>
    </button>

    <div class="notifications-ui-actions">
        @if ($isUnread)
            <button type="button" class="notifications-ui-action notifications-ui-action-success"
                wire:click.stop="markAsRead('{{ $notification['id'] }}')"
                aria-label="{{ __('components/notifications-ui.actions.mark_read') }}">
                <i class="fa-solid fa-check"></i>
            </button>
        @endif

        @if ($context === 'modal')
            <button type="button" class="notifications-ui-action notifications-ui-action-danger"
                wire:click.stop="deleteNotification('{{ $notification['id'] }}')"
                aria-label="{{ __('components/notifications-ui.actions.delete') }}">
                <i class="fa-regular fa-trash-can"></i>
            </button>
        @endif
    </div>
</article>
