<?php

namespace Tests\Feature\Search;

use App\Livewire\Admin\Search\DemoPostsTable;
use App\Livewire\Admin\Search\DemoProductsTable;
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

        $post = SearchPost::factory()->create([
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
        $result = $results->firstWhere('group', 'posts');
        $this->assertSame(__('components/search-engine.badges.post'), $result->badge);
        $this->assertSame('edit', $result->clickAction);
        $this->assertSame(route('search.demo.posts.edit', ['post' => $post->id]), $result->url);
        $this->assertTrue(collect($result->actions)->contains(fn (array $action) => $action['key'] === 'visit'));
        $this->assertFalse(collect($result->actions)->contains(fn (array $action) => $action['key'] === 'edit'));
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

    public function test_product_model_click_action_points_to_edit_and_visit_stays_as_secondary_action(): void
    {
        $product = SearchProduct::factory()->create([
            'name' => 'Kit de midias para loja',
            'description' => 'Produto publicado para validar actions.',
        ]);

        $result = app(SearchEngine::class)
            ->scope('admin')
            ->search('midias loja', group: 'products')
            ->firstWhere('title', 'Kit de midias para loja');

        $this->assertNotNull($result);
        $this->assertSame('edit', $result->clickAction);
        $this->assertSame(route('search.demo.products.edit', ['product' => $product->id]), $result->url);
        $this->assertTrue(collect($result->actions)->contains(fn (array $action) => $action['key'] === 'visit'));
        $this->assertFalse(collect($result->actions)->contains(fn (array $action) => $action['key'] === 'edit'));
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

    public function test_model_action_visibility_respects_visible_when_rules(): void
    {
        Config::set('search.scopes.admin.models.demo_posts.constraints', []);

        SearchPost::factory()->draft()->create([
            'title' => 'Rascunho pesquisavel para validar actions',
            'excerpt' => 'Este registro aparece na busca, mas nao pode ser visitado.',
            'body' => 'Actions com visible_when nao devem renderizar visita para rascunhos.',
        ]);

        $result = app(SearchEngine::class)
            ->scope('admin')
            ->search('rascunho pesquisavel')
            ->firstWhere('group', 'posts');

        $this->assertNotNull($result);
        $this->assertSame('edit', $result->clickAction);
        $this->assertSame([], $result->actions);
    }

    public function test_model_actions_can_be_hidden_without_disabling_click_action(): void
    {
        Config::set('search.scopes.admin.actions.demo_posts.show', false);

        SearchPost::factory()->create([
            'title' => 'Post com actions escondidas',
            'excerpt' => 'O clique continua ativo mesmo sem botoes.',
            'body' => 'A busca deve manter o atalho principal de edicao.',
        ]);

        $result = app(SearchEngine::class)
            ->scope('admin')
            ->search('actions escondidas')
            ->firstWhere('group', 'posts');

        $this->assertNotNull($result);
        $this->assertSame('edit', $result->clickAction);
        $this->assertSame([], $result->actions);
    }

    public function test_demo_edit_action_routes_render_edit_destinations(): void
    {
        $this->get(route('search.demo.posts.edit', ['post' => 10]))
            ->assertOk()
            ->assertSee(__('components/search-engine.demo_edit.posts.title'))
            ->assertSee('ID: 10');

        $this->get(route('search.demo.products.edit', ['product' => 20]))
            ->assertOk()
            ->assertSee(__('components/search-engine.demo_edit.products.title'))
            ->assertSee('ID: 20');
    }

    public function test_invalid_model_action_configuration_stops_execution(): void
    {
        Config::set('search.scopes.admin.actions.demo_posts.click', 'publish');

        $this->expectException(InvalidSearchConfigurationException::class);
        $this->expectExceptionMessage('admin.actions.demo_posts');
        $this->expectExceptionMessage('publish');

        app(SearchEngine::class)
            ->scope('admin')
            ->search('anything');
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

    public function test_livewire_table_scope_applies_search_to_existing_product_query(): void
    {
        SearchProduct::factory()->create([
            'name' => 'Kit editorial para loja',
            'description' => 'Produto publicado para validar pesquisa em tabela Livewire.',
            'status' => 'published',
        ]);

        SearchProduct::factory()->draft()->create([
            'name' => 'Kit editorial em rascunho',
            'description' => 'Este item tem o termo, mas deve respeitar o filtro aplicado pela tabela.',
        ]);

        $query = SearchProduct::query()->where('status', 'published');

        $products = app(SearchEngine::class)
            ->livewireTable('demo_products')
            ->apply($query, 'kit editorial')
            ->get();

        $this->assertCount(1, $products);
        $this->assertSame('Kit editorial para loja', $products->first()->name);
    }

    public function test_livewire_table_invalid_configuration_stops_execution(): void
    {
        Config::set('search.livewire_tables.demo_products.fields_weight', [
            'missing_field' => 100,
        ]);

        $this->expectException(InvalidSearchConfigurationException::class);
        $this->expectExceptionMessage('livewire_tables.demo_products');
        $this->expectExceptionMessage('missing_field');

        app(SearchEngine::class)
            ->livewireTable('demo_products')
            ->apply(SearchProduct::query(), 'anything')
            ->get();
    }

    public function test_livewire_product_table_uses_search_engine_scope(): void
    {
        SearchProduct::factory()->create([
            'name' => 'Painel pesquisavel para administradores',
            'description' => 'Registro publicado para tabela de produtos.',
        ]);

        SearchProduct::factory()->create([
            'name' => 'Produto comum sem termo',
            'description' => 'Registro que nao deve aparecer.',
        ]);

        Livewire::test(DemoProductsTable::class)
            ->set('search', 'painel pesquisavel')
            ->assertSee('Painel pesquisavel para administradores')
            ->assertDontSee('Produto comum sem termo');
    }

    public function test_livewire_post_table_keeps_filters_around_search(): void
    {
        SearchPost::factory()->create([
            'title' => 'Editor visual publicado',
            'excerpt' => 'Registro publicado para tabela de posts.',
            'body' => 'Conteudo pesquisavel.',
            'status' => 'published',
        ]);

        SearchPost::factory()->draft()->create([
            'title' => 'Editor visual em rascunho',
            'excerpt' => 'Registro em rascunho para tabela de posts.',
            'body' => 'Conteudo pesquisavel.',
        ]);

        Livewire::test(DemoPostsTable::class)
            ->set('status', 'draft')
            ->set('search', 'editor visual')
            ->assertSee('Editor visual em rascunho')
            ->assertDontSee('Editor visual publicado');
    }
}
