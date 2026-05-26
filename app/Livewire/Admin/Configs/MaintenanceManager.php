<?php

namespace App\Livewire\Admin\Configs;

use App\Livewire\Traits\WithAnimatedModals;
use App\Models\Configs\MaintenanceSetting;
use Livewire\Attributes\On;
use Livewire\Component;

class MaintenanceManager extends Component
{
    use WithAnimatedModals;

    public bool $maintenanceEnabled = false;

    public ?string $maintenanceMessage = null;

    public bool $showHeaderShortcut = false;

    public bool $showOnlineAlert = true;

    public int $onlineAlertDurationSeconds = MaintenanceSetting::DEFAULT_ONLINE_ALERT_DURATION_SECONDS;

    public function mount(): void
    {
        $this->fillFromSettings();
    }

    public function render()
    {
        return view('livewire.admin.configs.maintenance-manager');
    }

    public function saveSettings(): void
    {
        $this->persistSettings();

        $this->dispatch('maintenance-settings-updated')->to(MaintenanceHeaderStatus::class);

        session()->flash('updated', __('components/maintenance-mode.flash.updated'));
    }

    public function toggleMaintenance(string $action): void
    {
        if (! in_array($action, ['enable', 'disable'], true)) {
            return;
        }

        $this->persistSettings([
            'maintenance_enabled' => $action === 'enable',
            'maintenance_disabled_at' => $action === 'disable' ? now() : null,
        ]);

        $this->maintenanceEnabled = $action === 'enable';

        $this->dispatch('close-modal', id: $this->modalIdForAction($action));
        $this->dispatch('maintenance-settings-updated')->to(MaintenanceHeaderStatus::class);
        $this->resetModalData();

        session()->flash(
            $action === 'enable' ? 'error' : 'success',
            $action === 'enable'
                ? __('components/maintenance-mode.flash.enabled')
                : __('components/maintenance-mode.flash.disabled')
        );
    }

    protected function persistSettings(array $overrides = []): MaintenanceSetting
    {
        $data = $this->validate([
            'maintenanceMessage' => ['nullable', 'string', 'max:500'],
            'showHeaderShortcut' => ['boolean'],
            'showOnlineAlert' => ['boolean'],
            'onlineAlertDurationSeconds' => ['required', 'integer', 'min:0', 'max:86400'],
        ]);

        $setting = MaintenanceSetting::current();

        $setting->update(array_merge([
            'maintenance_message' => filled($data['maintenanceMessage'])
                ? $data['maintenanceMessage']
                : null,
            'show_header_shortcut' => $data['showHeaderShortcut'],
            'show_online_alert' => $data['showOnlineAlert'],
            'online_alert_duration_seconds' => $data['onlineAlertDurationSeconds'],
        ], $overrides));

        return MaintenanceSetting::refreshCurrent();
    }

    protected function fillFromSettings(): void
    {
        $settings = MaintenanceSetting::current();

        $this->maintenanceEnabled = $settings->maintenance_enabled ?? false;
        $this->maintenanceMessage = $settings->maintenance_message;
        $this->showHeaderShortcut = $settings->show_header_shortcut ?? false;
        $this->showOnlineAlert = $settings->show_online_alert ?? true;
        $this->onlineAlertDurationSeconds = $settings->online_alert_duration_seconds
            ?? MaintenanceSetting::DEFAULT_ONLINE_ALERT_DURATION_SECONDS;
    }

    #[On('maintenance-settings-updated')]
    public function refreshSettings(): void
    {
        $this->fillFromSettings();
    }

    protected function modalIdForAction($action)
    {
        return match ($action) {
            'enable', 'disable' => 'maintenance_toggle',
            default => null,
        };
    }

    public function resetModalData(): void
    {
        $this->modalAction = null;
    }
}
