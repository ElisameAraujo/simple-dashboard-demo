<?php

namespace Tests\Unit;

use App\Helpers\DiskHelper;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DiskHelperTest extends TestCase
{
    public function test_update_file_replaces_a_file_inside_subfolders(): void
    {
        Carbon::setTestNow('2026-05-21 18:30:00');
        Storage::fake('products');
        Storage::disk('products')->put('femininos/old.jpg', 'old image');

        try {
            $newPath = DiskHelper::updateFile(
                UploadedFile::fake()->image('New Product.jpg', 10, 10),
                'old.jpg',
                'products',
                'femininos'
            );

            $this->assertSame('femininos/new-product-20260521183000.jpg', $newPath);
            $this->assertFalse(Storage::disk('products')->exists('femininos/old.jpg'));
            $this->assertTrue(Storage::disk('products')->exists($newPath));
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_update_file_keeps_existing_root_file_behavior(): void
    {
        Carbon::setTestNow('2026-05-21 18:30:00');
        Storage::fake('public');
        Storage::disk('public')->put('old.jpg', 'old image');

        try {
            $newPath = DiskHelper::updateFile(
                UploadedFile::fake()->image('New Product.jpg', 10, 10),
                'old.jpg'
            );

            $this->assertSame('new-product-20260521183000.jpg', $newPath);
            $this->assertFalse(Storage::disk('public')->exists('old.jpg'));
            $this->assertTrue(Storage::disk('public')->exists($newPath));
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_update_file_accepts_nested_subfolders_as_an_array(): void
    {
        Carbon::setTestNow('2026-05-21 18:30:00');
        Storage::fake('products');
        Storage::disk('products')->put('femininos/marco/old.jpg', 'old image');

        try {
            $newPath = DiskHelper::updateFile(
                UploadedFile::fake()->image('Summer Dress.jpg', 10, 10),
                'old.jpg',
                'products',
                ['femininos', 'marco']
            );

            $this->assertSame('femininos/marco/summer-dress-20260521183000.jpg', $newPath);
            $this->assertFalse(Storage::disk('products')->exists('femininos/marco/old.jpg'));
            $this->assertTrue(Storage::disk('products')->exists($newPath));
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_save_file_accepts_nested_subfolders_as_an_array(): void
    {
        Carbon::setTestNow('2026-05-21 18:30:00');
        Storage::fake('products');

        try {
            $path = DiskHelper::saveFile(
                UploadedFile::fake()->image('Summer Dress.jpg', 10, 10),
                'products',
                ['femininos', 'marco']
            );

            $this->assertSame('femininos/marco/summer-dress-20260521183000.jpg', $path);
            $this->assertTrue(Storage::disk('products')->exists($path));
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_file_url_returns_a_public_url_when_the_file_exists(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('avatars/user.jpg', 'avatar');

        $this->assertSame('/storage/avatars/user.jpg', DiskHelper::fileUrl('user.jpg', 'public', 'avatars'));
        $this->assertNull(DiskHelper::fileUrl('missing.jpg', 'public', 'avatars'));
    }
}
