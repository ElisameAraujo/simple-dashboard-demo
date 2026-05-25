<div class="notifications-ui-modal-content">
    @include('livewire.global.partials.notifications-ui-modal', [
        'embedded' => false,
        'modalNotifications' => $modalNotifications,
        'unreadCount' => $unreadCount,
    ])
</div>
