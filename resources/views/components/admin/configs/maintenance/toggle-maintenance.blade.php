<dialog class="modal maintenance-toggle-modal" id="{{ $modalId ?? 'maintenance_toggle' }}">
    <div class="modal-box">
        @if ($modalAction === 'enable')
            <h3 class="modal-title error">{{ __('components/maintenance-mode.modal.enable_title') }}</h3>

            <p>{{ __('components/maintenance-mode.modal.enable_question') }}</p>
            <p>{{ __('components/maintenance-mode.modal.enable_description') }}</p>
        @else
            <h3 class="modal-title success">{{ __('components/maintenance-mode.modal.disable_title') }}</h3>

            <p>{{ __('components/maintenance-mode.modal.disable_question') }}</p>
            <p>{{ __('components/maintenance-mode.modal.disable_description') }}</p>
        @endif

        <form wire:submit.prevent="toggleMaintenance('{{ $modalAction }}')">
            <div class="modal-action">
                @if ($modalAction === 'enable')
                    <button type="submit" class="btn btn-error">
                        <i class="fa-solid fa-wrench"></i>
                        {{ __('components/maintenance-mode.actions.enable') }}
                    </button>
                @else
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-check"></i>
                        {{ __('components/maintenance-mode.actions.disable') }}
                    </button>
                @endif

                <button type="button" class="btn" wire:click="requestCloseModal">
                    {{ __('components/maintenance-mode.actions.cancel') }}
                </button>
            </div>
        </form>
    </div>

    <form method="dialog" class="modal-backdrop">
        <button wire:click="requestCloseModal">close</button>
    </form>
</dialog>
