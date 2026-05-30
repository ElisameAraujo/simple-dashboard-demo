<?php

namespace Tests\Feature\Modules;

use App\Livewire\Global\ImagePreview;
use App\Support\ModuleDemoCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Livewire\Livewire;
use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;

class ImagePreviewModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_modules_index_and_image_preview_page_are_available(): void
    {
        app()->setLocale('en');

        $this->get(route('modules.index'))
            ->assertOk()
            ->assertSee('Modules / Extras')
            ->assertSee('ImagePreview');

        $this->get(route('modules.show', 'image-preview'))
            ->assertOk()
            ->assertSee('ImagePreview')
            ->assertSee('Create mode')
            ->assertSee('Edit mode')
            ->assertSeeInOrder(['existing', 'bool', 'false'])
            ->assertSeeInOrder(['path', 'string|null', 'null']);
    }

    public function test_module_catalog_uses_yaml_documentation(): void
    {
        app()->setLocale('pt_BR');

        $module = ModuleDemoCatalog::find('image-preview');

        $this->assertSame('ImagePreview', $module['name']);
        $this->assertSame('global.image-preview', $module['component']);
        $this->assertSame('Pronto', $module['status_label']);
        $this->assertSame('Modo create', $module['variations'][0]['title']);
        $this->assertSame('mode', $module['configuration'][0]['name']);
        $this->assertSame('false', collect($module['configuration'])->firstWhere('name', 'existing')['default']);
        $this->assertSame('null', collect($module['configuration'])->firstWhere('name', 'path')['default']);
    }

    public function test_module_yaml_documentation_is_translated_with_matching_keys(): void
    {
        $english = Yaml::parseFile(resource_path('docs/modules/en/image-preview.yaml'));
        $portuguese = Yaml::parseFile(resource_path('docs/modules/pt_BR/image-preview.yaml'));

        $this->assertSame(
            array_keys(Arr::dot($english)),
            array_keys(Arr::dot($portuguese))
        );
    }

    public function test_image_preview_component_renders_create_and_edit_states(): void
    {
        app()->setLocale('en');

        Livewire::test(ImagePreview::class)
            ->assertSee('No image selected')
            ->assertSee('Select image');

        app()->setLocale('pt_BR');

        Livewire::test(ImagePreview::class, [
            'mode' => 'edit',
            'existing' => true,
            'path' => 'avatars/default-avatar.jpg',
            'disk' => 'public',
            'placeholder' => 'img/placeholders/avatars/default-avatar.jpg',
            'showSaveButton' => false,
        ])
            ->assertSee('Alterar imagem')
            ->assertDontSee('Remover imagem')
            ->assertDontSee('Salvar imagem');
    }
}
