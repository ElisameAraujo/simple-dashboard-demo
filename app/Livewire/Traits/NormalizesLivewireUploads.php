<?php

namespace App\Livewire\Traits;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait NormalizesLivewireUploads
{
    protected function normalizeUpload(string $property): void
    {
        if (! property_exists($this, $property)) {
            return;
        }

        if (TemporaryUploadedFile::canUnserialize($this->{$property})) {
            $this->{$property} = TemporaryUploadedFile::unserializeFromLivewireRequest($this->{$property});
        }
    }

    protected function normalizeUploads(array $properties): void
    {
        foreach ($properties as $property) {
            $this->normalizeUpload($property);
        }
    }
}
