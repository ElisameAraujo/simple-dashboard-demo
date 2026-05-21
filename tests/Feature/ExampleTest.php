<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_admin_dashboard_route_is_registered(): void
    {
        $this->assertSame(url('/admin'), route('admin.dashboard'));
    }

    public function test_the_admin_dashboard_page_renders_demo_summary(): void
    {
        app()->setLocale('pt_BR');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Resumo geral da demo')
            ->assertSee('Páginas disponíveis')
            ->assertSee('Links úteis')
            ->assertSee('Documentação dos helpers');
    }

    public function test_the_admin_dashboard_page_renders_in_english(): void
    {
        app()->setLocale('en');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('General demo summary')
            ->assertSee('Available pages')
            ->assertSee('Useful links')
            ->assertSee('Helper documentation');
    }

    public function test_the_admin_header_renders_a_language_switcher(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('English')
            ->assertSee('Português (Brasil)')
            ->assertSee(route('locale.switch', 'pt_BR'), false);
    }

    public function test_the_locale_switcher_updates_the_session_locale(): void
    {
        $this->from(route('admin.dashboard'))
            ->get(route('locale.switch', 'pt_BR'))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('locale', 'pt_BR');

        $this->withSession(['locale' => 'pt_BR'])
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Resumo geral da demo')
            ->assertSee('Português (Brasil)');
    }
}
