<?php

namespace Tests\Feature\Search;

use App\Livewire\Admin\Search\SearchSpotlight;
use App\Models\Demo\SearchPost;
use App\Models\Demo\SearchProduct;
use App\Search\Exceptions\InvalidSearchConfigurationException;
use App\Search\SearchEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Tests\TestCase;

class SearchEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_scope_returns_static_suggestions_from_config(): void
    {
        app()->setLocale('pt_BR');

        $results = app(SearchEngine::class)
            ->scope('admin')
            ->suggestions();

        $this->assertNotEmpty($results);
        $this->assertTrue($results->contains(fn ($result) => $result->key === 'admin.statics.dashboard'));
        $this->assertTrue($results->contains(fn ($result) => $result->url === route('configs.maintenance')));
        $this->assertSame('settings', $results->firstWhere('key', 'admin.statics.maintenance-mode')->group);
        $this->assertSame(__('ui.settings'), $results->firstWhere('key', 'admin.statics.maintenance-mode')->groupLabel);
    }

    public function test_admin_scope_searches_static_items_by_title_summary_and_keywords(): void
    {
        app()->setLocale('pt_BR');

        $results = app(SearchEngine::class)
            ->scope('admin')
            ->search('site offline');

        $this->assertSame('admin.statics.maintenance-mode', $results->first()->key);
        $this->assertSame(__('ui.maintenance'), $results->first()->title);
    }

    public function test_admin_scope_can_constrain_static_search_by_group(): void
    {
        app()->setLocale('pt_BR');

        $results = app(SearchEngine::class)
            ->scope('admin')
            ->search('notificações', group: 'account');

        $this->assertNotEmpty($results);
        $this->assertTrue($results->every(fn ($result) => $result->group === 'account'));
        $this->assertTrue($results->contains(fn ($result) => $result->key === 'admin.statics.account-notifications'));
        $this->assertFalse($results->contains(fn ($result) => $result->key === 'admin.statics.notifications-ui'));
    }

    public function test_admin_scope_respects_minimum_search_length(): void
    {
        $results = app(SearchEngine::class)
            ->scope('admin')
            ->search('m');

        $this->assertCount(0, $results);
    }

    public function test_invalid_static_configuration_stops_execution(): void
    {
        Config::set('search.scopes.admin.statics.broken', [
            'title' => 'Broken item',
            'route' => 'route.that.does.not.exist',
        ]);

        $this->expectException(InvalidSearchConfigurationException::class);
        $this->expectExceptionMessage('admin.statics.broken');

        app(SearchEngine::class)
            ->scope('admin')
            ->search('broken');
    }

    public function test_admin_scope_returns_model_results_from_config(): void
    {
        app()->setLocale('pt_BR');

        SearchPost::factory()->create([
            'title' => 'Editor visual para posts com imagens',
            'excerpt' => 'Conteudo publicado para validar a pesquisa em models.',
            'body' => 'O Spotlight encontra posts publicados pelo titulo, resumo e corpo.',
        ]);

        SearchPost::factory()->draft()->create([
            'title' => 'Editor visual em rascunho',
            'excerpt' => 'Este item nao deve aparecer porque nao foi publicado.',
            'body' => 'Mesmo contendo o termo editor, o constraint remove este registro.',
        ]);

        $results = app(SearchEngine::class)
            ->scope('admin')
            ->search('editor visual');

        $this->assertNotEmpty($results);
        $this->assertTrue($results->contains(fn ($result) => $result->source === 'models' && $result->group === 'posts'));
        $this->assertFalse($results->contains(fn ($result) => $result->title === 'Editor visual em rascunho'));
        $this->assertSame(__('components/search-engine.badges.post'), $results->firstWhere('group', 'posts')->badge);
    }

    public function test_admin_scope_can_constrain_model_search_by_group(): void
    {
        SearchPost::factory()->create([
            'title' => 'Midias para posts do blog',
            'excerpt' => 'Resultado de post.',
            'body' => 'Busca por midias dentro do conteudo editorial.',
        ]);

        SearchProduct::factory()->create([
            'name' => 'Kit de midias para loja',
            'description' => 'Resultado de produto publicado.',
        ]);

        $results = app(SearchEngine::class)
            ->scope('admin')
            ->search('midias', group: 'products');

        $this->assertNotEmpty($results);
        $this->assertTrue($results->every(fn ($result) => $result->group === 'products'));
        $this->assertTrue($results->contains(fn ($result) => $result->title === 'Kit de midias para loja'));
        $this->assertFalse($results->contains(fn ($result) => $result->title === 'Midias para posts do blog'));
    }

    public function test_model_field_weights_prioritize_stronger_fields(): void
    {
        SearchPost::factory()->create([
            'title' => 'Checklist editorial completo',
            'excerpt' => 'Um resumo sem o termo principal.',
            'body' => 'Conteudo generico sobre publicacao.',
        ]);

        SearchPost::factory()->create([
            'title' => 'Fluxo de publicacao',
            'excerpt' => 'Um resumo curto.',
            'body' => 'Checklist checklist checklist dentro do corpo do post.',
        ]);

        $results = app(SearchEngine::class)
            ->scope('admin')
            ->search('checklist');

        $this->assertSame('Checklist editorial completo', $results->first()->title);
    }

    public function test_invalid_model_field_weight_stops_execution(): void
    {
        Config::set('search.scopes.admin.models.demo_posts.fields_weight', [
            'missing_field' => 100,
        ]);

        $this->expectException(InvalidSearchConfigurationException::class);
        $this->expectExceptionMessage('admin.models.demo_posts');
        $this->expectExceptionMessage('missing_field');

        app(SearchEngine::class)
            ->scope('admin')
            ->search('anything');
    }

    public function test_unknown_model_field_stops_execution(): void
    {
        Config::set('search.scopes.admin.models.demo_posts.searchable_fields', [
            'title',
            'field_that_does_not_exist',
        ]);

        $this->expectException(InvalidSearchConfigurationException::class);
        $this->expectExceptionMessage('admin.models.demo_posts');
        $this->expectExceptionMessage('field_that_does_not_exist');

        app(SearchEngine::class)
            ->scope('admin')
            ->search('anything');
    }

    public function test_invalid_group_configuration_stops_execution(): void
    {
        Config::set('search.scopes.admin.statics.broken-group', [
            'title' => 'Broken group',
            'group' => 'does-not-exist',
            'route' => 'dashboard',
        ]);

        $this->expectException(InvalidSearchConfigurationException::class);
        $this->expectExceptionMessage('admin.statics.broken-group');
        $this->expectExceptionMessage('does-not-exist');

        app(SearchEngine::class)
            ->scope('admin')
            ->search('broken');
    }

    public function test_search_spotlight_renders_results_from_admin_scope(): void
    {
        app()->setLocale('pt_BR');

        Livewire::test(SearchSpotlight::class)
            ->call('open')
            ->set('term', 'manutenção')
            ->assertSet('isOpen', true)
            ->assertSee(__('ui.maintenance'))
            ->assertSee(__('ui.settings'))
            ->assertSee(__('components/search-engine.admin.maintenance-mode.summary'));
    }

    public function test_search_spotlight_hides_group_filters_until_search_starts(): void
    {
        app()->setLocale('pt_BR');

        Livewire::test(SearchSpotlight::class)
            ->call('open')
            ->assertSee(__('components/search-engine.spotlight.suggestions'))
            ->assertDontSee(__('components/search-engine.spotlight.all_groups'))
            ->set('term', 'manutenção')
            ->assertSee(__('components/search-engine.spotlight.all_groups'))
            ->assertSee(__('ui.settings'));
    }

    public function test_search_spotlight_filters_results_by_group(): void
    {
        app()->setLocale('pt_BR');

        Livewire::test(SearchSpotlight::class)
            ->call('open')
            ->set('term', 'notificações')
            ->assertSee('Notifications UI')
            ->call('selectGroup', 'account')
            ->assertSet('activeGroup', 'account')
            ->assertSee(__('ui.notifications'))
            ->assertDontSee('Notifications UI');
    }
}
