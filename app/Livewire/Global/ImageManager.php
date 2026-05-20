<?php

namespace App\Livewire\Global;

use Livewire\Attributes\Modelable;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ImageManager extends Component
{
    use WithFileUploads;

    public $mode = 'create';
    public $name = 'image';
    public $size = 'col-span-3';

    // Image Mount
    #[Modelable]
    public $image;
    public bool $existing = false;
    public $path;
    public $disk;
    public $placeholder;
    public ?bool $hasError = false;

    public function mount($placeholder = null, $disk = null, $path = null, $existing = false, $mode = 'create', $name = 'image', $size = 'col-span-3', $hasError = false)
    {
        $this->existing = $existing;
        $this->mode = $mode;
        $this->name = $name;
        $this->size = $size;
        $this->path = $path;
        $this->disk = $disk;
        $this->placeholder = $placeholder;
        $this->hasError = (bool) $hasError;
    }

    public function removeImage()
    {
        $this->image = null;
        $this->existing = false;
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

    public function render()
    {
        return view('livewire.global.image-manager');
    }
}
