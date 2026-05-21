<?php

namespace Tests\Unit;

use App\Helpers\MediaHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\TestCase;

class MediaHelperTest extends TestCase
{
    private string $mediaRoot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mediaRoot = storage_path('framework/testing/disks/media-helper');
        File::deleteDirectory($this->mediaRoot);

        config([
            'filesystems.disks.media-helper' => [
                'driver' => 'local',
                'root' => $this->mediaRoot,
                'url' => config('app.url') . '/storage/media-helper',
                'visibility' => 'public',
                'throw' => false,
                'report' => false,
            ],
        ]);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->mediaRoot);

        parent::tearDown();
    }

    public function test_media_exists_checks_the_given_disk_and_path(): void
    {
        Storage::disk('media-helper')->put('avatars/user.jpg', 'avatar');

        $this->assertTrue(MediaHelper::mediaExists('media-helper', 'avatars/user.jpg'));
        $this->assertFalse(MediaHelper::mediaExists('media-helper', 'avatars/missing.jpg'));
        $this->assertFalse(MediaHelper::mediaExists('media-helper'));
    }

    public function test_show_media_returns_the_disk_url_or_placeholder(): void
    {
        Storage::disk('media-helper')->put('avatars/user.jpg', 'avatar');

        $this->assertSame(
            '/storage/media-helper/avatars/user.jpg',
            MediaHelper::showMedia('avatars/user.jpg', 'media-helper')
        );

        $this->assertSame(
            asset('img/placeholders/avatar.png'),
            MediaHelper::showMedia('avatars/missing.jpg', 'media-helper', 'img/placeholders/avatar.png')
        );

        $this->assertNull(MediaHelper::showMedia('avatars/missing.jpg', 'media-helper'));
    }

    public function test_media_full_path_returns_the_url_without_app_url(): void
    {
        Storage::disk('media-helper')->put('documents/manual.pdf', 'manual');

        $this->assertSame(
            '/storage/media-helper/documents/manual.pdf',
            MediaHelper::mediaFullPath('documents/manual.pdf', 'media-helper')
        );
    }

    public function test_download_media_returns_a_binary_file_response(): void
    {
        Storage::disk('media-helper')->put('documents/manual.pdf', 'manual');

        $response = MediaHelper::downloadMedia('documents/manual.pdf', 'Manual.pdf', 'media-helper');

        $this->assertInstanceOf(BinaryFileResponse::class, $response);
        $this->assertStringContainsString('Manual.pdf', $response->headers->get('content-disposition'));
    }

    public function test_media_mime_type_returns_the_detected_type_or_not_found_message(): void
    {
        Storage::disk('media-helper')->put('documents/readme.txt', 'Plain text');

        $this->assertSame('text/plain', MediaHelper::mediaMimeType('documents/readme.txt', 'media-helper'));
        $this->assertSame(__('error_messages.file_not_found'), MediaHelper::mediaMimeType('documents/missing.txt', 'media-helper'));
    }
}
