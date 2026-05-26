<div class="section">
    @if (in_array($modalAction, ['enable', 'disable'], true))
        @include('components.admin.configs.maintenance.toggle-maintenance')
    @endif

    <div class="section-title">
        <h1>{{ __('components/maintenance-mode.title') }}</h1>
        <h4>{{ __('components/maintenance-mode.description') }}</h4>
    </div>

    <div class="section-content">
        <form wire:submit="saveSettings" class="profile-options">
            @include('components.global.flash-messages')

            <div class="profile-option">
                <div class="option flex gap-2">
                    <i class="fa-solid fa-bars-progress"></i>
                    {{ __('components/maintenance-mode.status.current') }}
                </div>
                <div class="action justify-end">
                    @if ($maintenanceEnabled)
                        <div class="project-status down">
                            <div class="inline-grid *:[grid-area:1/1]">
                                <div aria-label="status" class="status status-error animate-ping"></div>
                                <div aria-label="status" class="status status-error"></div>
                            </div> {{ __('components/maintenance-mode.status.down') }}
                        </div>
                    @else
                        <div class="project-status up">
                            <div class="inline-grid *:[grid-area:1/1]">
                                <div aria-label="status" class="status status-success animate-ping"></div>
                                <div aria-label="status" class="status status-success"></div>
                            </div> {{ __('components/maintenance-mode.status.up') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="profile-option">
                <div class="option flex gap-2">
                    <i class="fa-solid fa-wrench"></i>
                    {{ __('components/maintenance-mode.actions.toggle') }}
                </div>
                <div class="action justify-end">
                    @if ($maintenanceEnabled)
                        <button type="button" class="btn btn-success" wire:click="openModal('disable')">
                            {{ __('components/maintenance-mode.actions.disable') }}
                        </button>
                    @else
                        <button type="button" class="btn btn-error" wire:click="openModal('enable')">
                            {{ __('components/maintenance-mode.actions.enable') }}
                        </button>
                    @endif
                </div>
            </div>

            <div class="profile-option">
                <div class="option flex gap-2">
                    <i class="fa-regular fa-message"></i>
                    {{ __('components/maintenance-mode.message.label') }}
                </div>
                <div class="action">
                    <fieldset class="fieldset w-full">
                        <textarea class="textarea w-full" rows="4" maxlength="500" wire:model="maintenanceMessage"
                            placeholder="{{ __('components/maintenance-mode.message.placeholder') }}"></textarea>

                        @error('maintenanceMessage')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>
            </div>

            <div class="profile-option">
                <div class="option flex gap-2">
                    <i class="fa-solid fa-bolt"></i>
                    {{ __('components/maintenance-mode.header_shortcut.label') }}

                </div>
                <div class="action">
                    <fieldset class="fieldset">
                        <label class="label">
                            <input type="checkbox" wire:model="showHeaderShortcut"
                                class="checkbox checkbox-sm rounded checkbox-primary" />
                            {{ __('components/maintenance-mode.header_shortcut.checkbox') }}
                        </label>
                        <p class="label">{{ __('components/maintenance-mode.header_shortcut.description') }}</p>

                        @error('showHeaderShortcut')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>
            </div>

            <div class="profile-option">
                <div class="option flex gap-2">
                    <i class="fa-regular fa-circle-check"></i>
                    {{ __('components/maintenance-mode.online_alert.label') }}
                </div>
                <div class="action">
                    <fieldset class="fieldset">
                        <label class="label">
                            <input type="checkbox" wire:model.live="showOnlineAlert"
                                class="checkbox checkbox-sm rounded checkbox-primary" />
                            {{ __('components/maintenance-mode.online_alert.checkbox') }}
                        </label>

                        <label class="label gap-2 justify-start">
                            {{ __('components/maintenance-mode.online_alert.duration_prefix') }}
                            <input type="number" min="0" max="86400" step="1"
                                wire:model="onlineAlertDurationSeconds" @disabled(! $showOnlineAlert)
                                class="input input-sm w-24" />
                            {{ __('components/maintenance-mode.online_alert.duration_suffix') }}
                        </label>

                        <p class="label">{{ __('components/maintenance-mode.online_alert.description') }}</p>

                        @error('showOnlineAlert')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror

                        @error('onlineAlertDurationSeconds')
                            <span class="text-error text-sm">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>
            </div>

            <div class="profile-option">
                <div class="option"></div>
                <div class="action">
                    <fieldset class="fieldset">
                        <button type="submit" class="btn btn-soft btn-success w-fit">
                            {{ __('ui.save_changes') }}
                        </button>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
</div>
