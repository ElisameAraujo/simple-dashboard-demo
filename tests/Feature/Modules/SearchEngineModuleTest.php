<?php

namespace Tests\Feature\Modules;

use App\Support\ModuleDemoCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;

class SearchEngineModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_modules_index_and_search_engine_page_are_available(): void
    {
        app()->setLocale('en');

        $this->get(route('modules.index'))
            ->assertOk()
            ->assertSee('Search Engine');

        $this->get(route('modules.show', 'search-engine'))
            ->assertOk()
            ->assertSee('Search Engine')
            ->assertSee('Overview')
            ->assertDontSee('Static items');

        $this->get(route('modules.search-engine.section', 'static-sources'))
            ->assertOk()
            ->assertSee('Static Sources')
            ->assertSee('Static items');

        $this->get(route('modules.search-engine.section', 'livewire-tables'))
            ->assertOk()
            ->assertSee('Livewire Tables')
            ->assertSee('ProductTable demo')
            ->assertSee('PostTable demo');

        $this->get(route('modules.search-engine.section', 'web-search'))
            ->assertOk()
            ->assertSee('Web Search')
            ->assertSee('Results page');
    }

    public function test_module_catalog_uses_search_engine_yaml_documentation(): void
    {
        app()->setLocale('pt_BR');

        $module = ModuleDemoCatalog::find('search-engine');

        $this->assertSame('Search Engine', $module['name']);
        $this->assertNull($module['component']);
        $this->assertSame('Pronto', $module['status_label']);
        $this->assertSame('Visão Geral', $module['sections'][0]['title']);
        $this->assertSame('Spotlight', $module['sections'][5]['title']);
        $this->assertSame('Ações no Spotlight', $module['sections'][6]['title']);
        $this->assertSame('Busca Web', $module['sections'][7]['title']);
        $this->assertSame('Tabelas Livewire', $module['sections'][8]['title']);
        $this->assertSame('Evolução do Livewire', $module['sections'][10]['items'][1]['title']);
        $this->assertSame('Visão Geral', $module['documentation_pages'][0]['title']);
        $this->assertSame(route('modules.search-engine.section', 'livewire-tables'), $module['documentation_pages'][8]['url']);
    }

    public function test_search_engine_yaml_documentation_is_translated_with_matching_keys(): void
    {
        $english = Yaml::parseFile(resource_path('docs/modules/en/search-engine.yaml'));
        $portuguese = Yaml::parseFile(resource_path('docs/modules/pt_BR/search-engine.yaml'));

        $this->assertSame(
            array_keys(Arr::dot($english)),
            array_keys(Arr::dot($portuguese))
        );
    }
}
