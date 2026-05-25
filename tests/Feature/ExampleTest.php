<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_dashboard_route_is_registered(): void
    {
        $this->assertSame(url('/'), route('dashboard'));
    }

    public function test_the_dashboard_page_renders_demo_summary(): void
    {
        app()->setLocale('pt_BR');

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Resumo geral da demo')
            ->assertSee('Páginas disponíveis')
            ->assertSee('Documentação interna das classes helper do dashboard.')
            ->assertSee('Links úteis')
            ->assertSee('Documentação dos helpers');
    }

    public function test_the_dashboard_page_renders_in_english(): void
    {
        app()->setLocale('en');

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('General demo summary')
            ->assertSee('Available pages')
            ->assertSee('Internal documentation for the dashboard helper classes.')
            ->assertSee('Useful links')
            ->assertSee('Helper documentation');
    }

    public function test_the_helpers_section_lists_registered_helpers(): void
    {
        app()->setLocale('pt_BR');

        $this->get(route('helpers.index'))
            ->assertOk()
            ->assertSee('Estrutura base')
            ->assertSee('DateHelper')
            ->assertSee('HTMLHelper')
            ->assertSee('RuleHelper')
            ->assertSee('1 método');
    }

    public function test_the_helper_detail_page_documents_methods_and_examples(): void
    {
        app()->setLocale('pt_BR');

        $this->get(route('helpers.show', 'date-helper'))
            ->assertOk()
            ->assertSee('Como funciona')
            ->assertSee('Métodos disponíveis')
            ->assertSee('simpleDate')
            ->assertSee('DateHelper::simpleDate(&#039;2026-05-19&#039;, &#039;pt-BR&#039;);', false)
            ->assertSee('19/05/2026')
            ->assertSee('mockup-code');
    }

    public function test_an_unknown_helper_page_returns_not_found(): void
    {
        $this->get('/helpers/unknown-helper')
            ->assertNotFound();
    }

    public function test_the_header_renders_a_language_switcher(): void
    {
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('English')
            ->assertSee('Português (Brasil)')
            ->assertSee(route('locale.switch', 'pt_BR'), false);
    }

    public function test_the_header_renders_live_notifications_ui(): void
    {
        app()->setLocale('en');

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Order approved')
            ->assertSee('View all notifications')
            ->assertDontSee('Item 1')
            ->assertDontSee('Item 2');
    }

    public function test_the_locale_switcher_updates_the_session_locale(): void
    {
        $this->from(route('dashboard'))
            ->get(route('locale.switch', 'pt_BR'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('locale', 'pt_BR');

        $this->withSession(['locale' => 'pt_BR'])
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Resumo geral da demo')
            ->assertSee('Português (Brasil)');
    }
}
