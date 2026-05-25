<?php

namespace App\Livewire\Global;

use App\Helpers\MediaHelper;
use Livewire\Attributes\Modelable;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ImagePreview extends Component
{
    use WithFileUploads;

    public string $mode = 'create';
    public string $name = 'image';
    public string $size = 'col-span-3';
    public ?string $path = null;
    public ?string $disk = 'public';
    public ?string $placeholder = null;
    public string $accept = 'image/*';
    public bool $existing = false;
    public bool $hasError = false;
    public bool $showSaveButton = false;

    #[Modelable]
    public mixed $image = null;

    public function mount(
        ?string $placeholder = null,
        ?string $disk = 'public',
        ?string $path = null,
        bool $existing = false,
        string $mode = 'create',
        string $name = 'image',
        string $size = 'col-span-3',
        bool $hasError = false,
        string $accept = 'image/*',
        ?bool $showSaveButton = null,
    ): void {
        $this->placeholder = $placeholder;
        $this->disk = $disk;
        $this->path = $path;
        $this->existing = $existing;
        $this->mode = $mode;
        $this->name = $name;
        $this->size = $size;
        $this->hasError = $hasError;
        $this->accept = $accept;
        $this->showSaveButton = $showSaveButton ?? $mode === 'edit';
    }

    public function previewImage(): ?TemporaryUploadedFile
    {
        if ($this->image instanceof TemporaryUploadedFile) {
            return $this->image;
        }

        if (TemporaryUploadedFile::canUnserialize($this->image)) {
            $image = TemporaryUploadedFile::unserializeFromLivewireRequest($this->image);

            return $image instanceof TemporaryUploadedFile ? $image : null;
        }

        return null;
    }

    public function existingImageUrl(): ?string
    {
        if (! $this->existing) {
            return null;
        }

        if (! filled($this->path)) {
            return $this->placeholder ? asset($this->placeholder) : null;
        }

        return MediaHelper::showMedia($this->path, $this->disk, $this->placeholder);
    }

    public function render()
    {
        return view('livewire.global.image-preview');
    }
}
