<div class="maintenance-header-status maintenance-header-status-{{ $variant }} flex items-center gap-2"
    @if ($shouldPollOnlineAlert) wire:poll.5s="refreshSettings" @endif>
    @if ($maintenanceEnabled)
        <div class="project-status down">
            <div class="inline-grid *:[grid-area:1/1]">
                <div aria-label="status" class="status status-error animate-ping"></div>
                <div aria-label="status" class="status status-error"></div>
            </div>
            {{ __('components/maintenance-mode.status.down') }}
        </div>
    @elseif ($showOnlineBadge)
        <div class="project-status up">
            <div class="inline-grid *:[grid-area:1/1]">
                <div aria-label="status" class="status status-success animate-ping"></div>
                <div aria-label="status" class="status status-success"></div>
            </div>
            {{ __('components/maintenance-mode.status.up') }}
        </div>
    @endif
    @if (in_array($modalAction, ['enable', 'disable'], true))
        @include('components.admin.configs.maintenance.toggle-maintenance', [
            'modalId' => $modalId,
        ])
    @endif


    @if ($showHeaderShortcut)
        @if ($maintenanceEnabled)
            <button type="button" @class(['actions', 'tooltip' => $variant === 'header'])
                @if ($variant === 'header') data-tip="{{ __('components/maintenance-mode.actions.disable_shortcut') }}" @endif
                aria-label="{{ __('components/maintenance-mode.actions.disable_shortcut') }}"
                wire:click="openModal('disable')">
                <i class="fa-solid fa-check"></i>
            </button>
        @else
            <button type="button" @class(['actions', 'tooltip' => $variant === 'header'])
                @if ($variant === 'header') data-tip="{{ __('components/maintenance-mode.actions.enable_shortcut') }}" @endif
                aria-label="{{ __('components/maintenance-mode.actions.enable_shortcut') }}"
                wire:click="openModal('enable')">
                <i class="fa-solid fa-wrench"></i>
            </button>
        @endif
    @endif
</div>
