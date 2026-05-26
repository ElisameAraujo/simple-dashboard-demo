<?php

namespace App\Livewire\Admin\Configs;

use App\Livewire\Traits\WithAnimatedModals;
use App\Models\Configs\MaintenanceSetting;
use Livewire\Attributes\On;
use Livewire\Component;

class MaintenanceHeaderStatus extends Component
{
    use WithAnimatedModals;

    public bool $maintenanceEnabled = false;

    public bool $showHeaderShortcut = false;

    public bool $showOnlineAlert = true;

    public int $onlineAlertDurationSeconds = MaintenanceSetting::DEFAULT_ONLINE_ALERT_DURATION_SECONDS;

    public bool $showOnlineBadge = false;

    public bool $shouldPollOnlineAlert = false;

    public function mount(): void
    {
        $this->refreshSettings();
    }

    public function render()
    {
        return view('livewire.admin.configs.maintenance-header-status');
    }

    #[On('maintenance-settings-updated')]
    public function refreshSettings(): void
    {
        $settings = MaintenanceSetting::current();

        $this->maintenanceEnabled = $settings->maintenance_enabled ?? false;
        $this->showHeaderShortcut = $settings->show_header_shortcut ?? false;
        $this->showOnlineAlert = $settings->show_online_alert ?? true;
        $this->onlineAlertDurationSeconds = $settings->online_alert_duration_seconds
            ?? MaintenanceSetting::DEFAULT_ONLINE_ALERT_DURATION_SECONDS;
        $this->showOnlineBadge = $settings->shouldShowOnlineAlert();
        $this->shouldPollOnlineAlert = $settings->shouldPollOnlineAlert();
    }

    public function toggleMaintenance(string $action): void
    {
        if (! in_array($action, ['enable', 'disable'], true)) {
            return;
        }

        $setting = MaintenanceSetting::current();

        $setting->update([
            'maintenance_enabled' => $action === 'enable',
            'maintenance_disabled_at' => $action === 'disable' ? now() : null,
        ]);

        MaintenanceSetting::refreshCurrent();

        $this->dispatch('close-modal', id: $this->modalIdForAction($action));
        $this->refreshSettings();
        $this->dispatch('maintenance-settings-updated')->to(MaintenanceManager::class);
        $this->resetModalData();

        session()->flash(
            $action === 'enable' ? 'error' : 'success',
            $action === 'enable'
                ? __('components/maintenance-mode.flash.enabled')
                : __('components/maintenance-mode.flash.disabled')
        );
    }

    protected function modalIdForAction($action)
    {
        return match ($action) {
            'enable', 'disable' => 'header_maintenance_toggle',
            default => null,
        };
    }

    public function resetModalData(): void
    {
        $this->modalAction = null;
    }
}
