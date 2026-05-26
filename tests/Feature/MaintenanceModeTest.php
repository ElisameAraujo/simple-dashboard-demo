<?php

namespace Tests\Feature;

use App\Livewire\Admin\Configs\MaintenanceManager;
use App\Livewire\Admin\Configs\MaintenanceHeaderStatus;
use App\Models\Configs\MaintenanceSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::clear();
    }

    public function test_visitor_receives_503_when_maintenance_mode_is_enabled(): void
    {
        $this->enableMaintenance(message: 'Voltamos em instantes.');

        $this->get(route('web.preview'))
            ->assertStatus(503)
            ->assertSee('Voltamos em instantes.');
    }

    public function test_authenticated_user_can_access_public_site_during_maintenance(): void
    {
        $this->enableMaintenance();

        $this->actingAs(User::factory()->create())
            ->get(route('web.preview'))
            ->assertOk();
    }

    public function test_admin_can_update_settings_and_toggle_maintenance_mode(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(MaintenanceManager::class)
            ->set('maintenanceMessage', 'Manutenção programada.')
            ->set('showHeaderShortcut', true)
            ->set('showOnlineAlert', true)
            ->set('onlineAlertDurationSeconds', 60)
            ->call('toggleMaintenance', 'enable')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('maintenance_settings', [
            'maintenance_enabled' => true,
            'maintenance_message' => 'Manutenção programada.',
            'show_header_shortcut' => true,
            'show_online_alert' => true,
            'online_alert_duration_seconds' => 60,
        ]);

        Livewire::actingAs($user)
            ->test(MaintenanceManager::class)
            ->call('toggleMaintenance', 'disable')
            ->assertHasNoErrors();

        $settings = MaintenanceSetting::refreshCurrent();

        $this->assertFalse($settings->maintenance_enabled);
        $this->assertNotNull($settings->maintenance_disabled_at);
    }

    public function test_header_shows_maintenance_status_and_shortcut_when_configured(): void
    {
        $this->enableMaintenance();

        MaintenanceSetting::current()->update([
            'show_header_shortcut' => true,
        ]);

        MaintenanceSetting::refreshCurrent();

        Livewire::test(MaintenanceHeaderStatus::class)
            ->assertSee(__('components/maintenance-mode.status.down'))
            ->assertSee(__('components/maintenance-mode.actions.disable_shortcut'));
    }

    public function test_mobile_header_status_respects_the_maintenance_shortcut_setting(): void
    {
        MaintenanceSetting::current()->update([
            'maintenance_enabled' => false,
            'show_header_shortcut' => false,
        ]);

        MaintenanceSetting::refreshCurrent();

        Livewire::test(MaintenanceHeaderStatus::class, [
            'variant' => 'mobile',
            'modalId' => 'mobile_maintenance_toggle',
        ])
            ->assertDontSee(__('components/maintenance-mode.actions.enable_shortcut'));

        MaintenanceSetting::current()->update([
            'show_header_shortcut' => true,
        ]);

        MaintenanceSetting::refreshCurrent();

        Livewire::test(MaintenanceHeaderStatus::class, [
            'variant' => 'mobile',
            'modalId' => 'mobile_maintenance_toggle',
        ])
            ->assertSee(__('components/maintenance-mode.actions.enable_shortcut'))
            ->call('openModal', 'enable')
            ->assertSeeHtml('id="mobile_maintenance_toggle"');
    }

    public function test_online_alert_respects_configured_duration(): void
    {
        MaintenanceSetting::current()->update([
            'maintenance_enabled' => false,
            'show_online_alert' => true,
            'online_alert_duration_seconds' => 30,
            'maintenance_disabled_at' => now()->subSeconds(31),
        ]);

        MaintenanceSetting::refreshCurrent();

        Livewire::test(MaintenanceHeaderStatus::class)
            ->assertDontSee(__('components/maintenance-mode.status.up'));

        MaintenanceSetting::current()->update([
            'maintenance_disabled_at' => now()->subSeconds(10),
        ]);

        MaintenanceSetting::refreshCurrent();

        Livewire::test(MaintenanceHeaderStatus::class)
            ->assertSee(__('components/maintenance-mode.status.up'));
    }

    public function test_online_alert_can_be_always_visible_or_disabled(): void
    {
        MaintenanceSetting::current()->update([
            'maintenance_enabled' => false,
            'show_online_alert' => true,
            'online_alert_duration_seconds' => 0,
            'maintenance_disabled_at' => null,
        ]);

        MaintenanceSetting::refreshCurrent();

        Livewire::test(MaintenanceHeaderStatus::class)
            ->assertSee(__('components/maintenance-mode.status.up'));

        MaintenanceSetting::current()->update([
            'show_online_alert' => false,
        ]);

        MaintenanceSetting::refreshCurrent();

        Livewire::test(MaintenanceHeaderStatus::class)
            ->assertDontSee(__('components/maintenance-mode.status.up'));
    }

    protected function enableMaintenance(?string $message = null): void
    {
        MaintenanceSetting::current()->update([
            'maintenance_enabled' => true,
            'maintenance_message' => $message,
        ]);

        MaintenanceSetting::refreshCurrent();
    }
}
