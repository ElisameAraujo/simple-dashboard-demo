<?php

namespace Tests\Feature\Modules;

use App\Services\Media\RichTextMediaManager;
use App\Support\ModuleDemoCatalog;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;

class RichTextMediaModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_modules_index_and_rich_text_media_pages_are_available(): void
    {
        app()->setLocale('en');

        $this->get(route('modules.index'))
            ->assertOk()
            ->assertSee('Rich Text Media');

        $this->get(route('modules.show', 'rich-text-media'))
            ->assertOk()
            ->assertSee('Rich Text Media')
            ->assertSee('Overview')
            ->assertDontSee('images_upload_handler');

        $this->get(route('modules.section', ['rich-text-media', 'tinymce']))
            ->assertOk()
            ->assertSee('TinyMCE')
            ->assertSee('images_upload_handler');

        $this->get(route('modules.section', ['rich-text-media', 'ckeditor']))
            ->assertOk()
            ->assertSee('CKEditor')
            ->assertSee('UploadAdapter');

        $this->get(route('modules.section', ['rich-text-media', 'project-implementation']))
            ->assertOk()
            ->assertSee('Project Implementation')
            ->assertSee('Scheduled cleanup');
    }

    public function test_module_catalog_uses_rich_text_media_yaml_documentation(): void
    {
        app()->setLocale('pt_BR');

        $module = ModuleDemoCatalog::find('rich-text-media');

        $this->assertSame('Rich Text Media', $module['name']);
        $this->assertNull($module['component']);
        $this->assertSame('Pronto', $module['status_label']);
        $this->assertSame('Visão Geral', $module['sections'][0]['title']);
        $this->assertSame('TinyMCE', $module['sections'][5]['title']);
        $this->assertSame('CKEditor', $module['sections'][6]['title']);
        $this->assertSame('Quill', $module['sections'][7]['title']);
        $this->assertSame('Froala', $module['sections'][8]['title']);
        $this->assertSame('Tiptap', $module['sections'][9]['title']);
        $this->assertSame('Lexical', $module['sections'][10]['title']);
        $this->assertSame(route('modules.section', ['rich-text-media', 'tinymce']), $module['documentation_pages'][5]['url']);
    }

    public function test_rich_text_media_yaml_documentation_is_translated_with_matching_keys(): void
    {
        $english = Yaml::parseFile(resource_path('docs/modules/en/rich-text-media.yaml'));
        $portuguese = Yaml::parseFile(resource_path('docs/modules/pt_BR/rich-text-media.yaml'));

        $this->assertSame(
            array_keys(Arr::dot($english)),
            array_keys(Arr::dot($portuguese))
        );
    }

    public function test_upload_endpoint_returns_generic_editor_response(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');
        Storage::fake('rich-text-test');

        config()->set('filesystems.disks.rich-text-test', [
            'driver' => 'local',
            'root' => storage_path('framework/testing/disks/rich-text-test'),
            'url' => '/storage/rich-text-test',
        ]);

        $this->postJson(route('admin.rich-text-media.uploads.store'), [
            'file' => UploadedFile::fake()->image('Inline Image.jpg', 20, 20),
            'disk' => 'rich-text-test',
            'mode' => 'temporary',
            'temporary_key' => 'draft-123',
        ])
            ->assertOk()
            ->assertJsonPath('path', 'temp/draft-123/inline-image-20260609120000.jpg')
            ->assertJsonStructure(['url', 'location', 'path']);

        Storage::disk('rich-text-test')
            ->assertExists('temp/draft-123/inline-image-20260609120000.jpg');
    }

    public function test_manager_commits_temporary_images_and_syncs_owner_folder(): void
    {
        Carbon::setTestNow('2026-06-09 12:00:00');
        Storage::fake('rich-text-test');

        $manager = app(RichTextMediaManager::class);
        $uploaded = $manager->uploadTemporaryImage(
            UploadedFile::fake()->image('Inline Image.jpg', 20, 20),
            'rich-text-test',
            'draft-123'
        );

        $html = '<p><img src="'.$uploaded['url'].'" alt=""></p>';
        $committedHtml = $manager->commitTemporaryImages('rich-text-test', 'draft-123', 15, $html);

        Storage::disk('rich-text-test')
            ->assertMissing('temp/draft-123/inline-image-20260609120000.jpg');

        Storage::disk('rich-text-test')
            ->assertExists('15/inline-image-20260609120000.jpg');

        $this->assertStringContainsString('15/inline-image-20260609120000.jpg', $committedHtml);
    }

    public function test_manager_removes_owner_images_not_referenced_in_html(): void
    {
        Storage::fake('rich-text-test');

        Storage::disk('rich-text-test')->put('15/keep.jpg', 'keep');
        Storage::disk('rich-text-test')->put('15/remove.jpg', 'remove');

        $html = '<p><img src="/storage/rich-text-test/15/keep.jpg" alt=""></p>';

        app(RichTextMediaManager::class)->syncOwnerImages('rich-text-test', 15, $html);

        Storage::disk('rich-text-test')->assertExists('15/keep.jpg');
        Storage::disk('rich-text-test')->assertMissing('15/remove.jpg');
    }
}
