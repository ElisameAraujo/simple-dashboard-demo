@php
    $inputId = 'image_input_' . $this->id();
    $previewImage = $this->previewImage();
    $existingImageUrl = $this->existingImageUrl();
    $hasActiveImage = filled($previewImage) || filled($existingImageUrl);
@endphp

<div class="image-preview {{ $size }}">
    <div class="preview border-dashed @if (($hasError ?? false) || $errors->get($name)) border-red-500! ring-2 ring-red-500/20! @endif">
        @if ($previewImage)
            <img src="{{ $previewImage->temporaryUrl() }}" alt="{{ __('components/image-preview.preview_alt') }}">
        @elseif ($existingImageUrl)
            <img src="{{ $existingImageUrl }}" alt="{{ __('components/image-preview.preview_alt') }}">
        @else
            <div class="no-image">
                <i class="fa-regular fa-image"></i>
                <span>{{ __('components/image-preview.empty') }}</span>
            </div>
        @endif
    </div>

    <input type="file" class="hidden" wire:model.live="image" id="{{ $inputId }}" name="{{ $name }}"
        accept="{{ $accept }}">

    <div class="image-preview-actions">
        <label class="btn btn-success" for="{{ $inputId }}">
            <i class="fa-solid fa-upload"></i>
            {{ $hasActiveImage ? __('components/image-preview.actions.change') : __('components/image-preview.actions.select') }}
        </label>

        @if ($mode === 'edit' && $showSaveButton)
            <button class="btn btn-primary" type="submit">
                <i class="fa-solid fa-floppy-disk"></i>
                {{ __('components/image-preview.actions.save') }}
            </button>
        @endif
    </div>

    @error($name)
        <p class="validation-fail-text text-xs">{{ Str::replace('.', '', $message) }}</p>
    @enderror

    <div wire:loading wire:target="image" class="image-preview-loading">
        <span class="loading loading-spinner loading-md"></span>
        {{ __('components/image-preview.loading') }}
    </div>
</div>
