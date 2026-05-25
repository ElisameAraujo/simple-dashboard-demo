<?php

namespace Tests\Feature\Modules;

use App\Livewire\Global\NotificationsUi;
use App\Livewire\Global\NotificationsUiModal;
use App\Support\ModuleDemoCatalog;
use Illuminate\Support\Arr;
use Livewire\Livewire;
use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;

class NotificationsUiModuleTest extends TestCase
{
    public function test_modules_index_and_notifications_ui_page_are_available(): void
    {
        app()->setLocale('en');

        $this->get(route('modules.index'))
            ->assertOk()
            ->assertSee('Notifications UI');

        $this->get(route('modules.show', 'notifications-ui'))
            ->assertOk()
            ->assertSee('Notifications UI')
            ->assertSee('Compact dropdown')
            ->assertSee('Full list')
            ->assertSee('Empty state');
    }

    public function test_module_catalog_uses_notifications_ui_yaml_documentation(): void
    {
        app()->setLocale('pt_BR');

        $module = ModuleDemoCatalog::find('notifications-ui');

        $this->assertSame('Notifications UI', $module['name']);
        $this->assertSame('global.notifications-ui', $module['component']);
        $this->assertSame('Pronto', $module['status_label']);
        $this->assertSame('Dropdown compacto', $module['variations'][0]['title']);
        $this->assertSame('scenario', $module['configuration'][0]['name']);
        $this->assertSame('markAsRead()', $module['methods'][0]['name']);
    }

    public function test_notifications_ui_yaml_documentation_is_translated_with_matching_keys(): void
    {
        $english = Yaml::parseFile(resource_path('docs/modules/en/notifications-ui.yaml'));
        $portuguese = Yaml::parseFile(resource_path('docs/modules/pt_BR/notifications-ui.yaml'));

        $this->assertSame(
            array_keys(Arr::dot($english)),
            array_keys(Arr::dot($portuguese))
        );
    }

    public function test_notifications_ui_component_renders_fake_notifications_and_actions(): void
    {
        app()->setLocale('en');

        Livewire::test(NotificationsUi::class)
            ->assertSee('Order approved')
            ->assertSee('New message')
            ->assertSee('Mark all as read')
            ->call('markAsRead', 'demo-order-approved')
            ->assertDontSee('Order approved')
            ->call('openModal')
            ->assertDispatched('openModal');
    }

    public function test_notifications_ui_modal_renders_the_complete_list_and_closes(): void
    {
        app()->setLocale('en');

        Livewire::test(NotificationsUiModal::class)
            ->assertSee('Mocked admin notifications for the demo flow.')
            ->assertSee('Comment pending')
            ->call('setFilter', 'read')
            ->assertSee('Backup completed')
            ->call('deleteRead')
            ->assertDontSee('Backup completed')
            ->call('closeModal')
            ->assertDispatched('closeModal');
    }

    public function test_notifications_ui_empty_state_is_available(): void
    {
        app()->setLocale('pt_BR');

        Livewire::test(NotificationsUi::class, ['scenario' => 'empty'])
            ->assertSee('Nenhuma notificação nova.')
            ->call('openModal')
            ->assertDispatched('openModal');
    }

    public function test_notifications_ui_header_variant_opens_the_complete_list(): void
    {
        app()->setLocale('en');

        Livewire::test(NotificationsUi::class, ['variant' => 'header'])
            ->assertSee('Order approved')
            ->assertSee('View all notifications')
            ->call('openModal')
            ->assertDispatched('openModal');
    }
}
