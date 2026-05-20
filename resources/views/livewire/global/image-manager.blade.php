<div class="image-preview {{ $size }}">
    @php($previewImage = $this->previewImage())

    {{-- Área de preview --}}
    <div class="preview border-dashed @if (($hasError ?? false) || $errors->get($name)) border-red-500! ring-2 ring-red-500/20! @endif"
        id="preview">

        @if ($previewImage)
            {{-- Nova imagem selecionada --}}
            <img src="{{ $previewImage->temporaryUrl() }}" id="image-active-preview">
        @elseif ($existing)
            {{-- Imagem existente (edição) --}}
            <img src="{{ MediaHelper::showMedia($path, $disk, $placeholder) }}" id="image-active-preview">
        @else
            {{-- Nenhuma imagem --}}
            <div class="no-image">Nenhuma Imagem Selecionada</div>
        @endif
    </div>

    {{-- Input invisível --}}
    <input type="file" class="hidden" wire:model.live="image" id="image_input_{{ $this->id() }}"
        name="{{ $name }}">

    {{-- Botão: Selecionar imagem (sempre visível) --}}
    @if ($mode === 'create')
        <button class="btn btn-success" type="button"
            onclick="document.getElementById('image_input_{{ $this->id() }}').click()">
            <i class="fa-solid fa-upload"></i> Selecionar Imagem
        </button>
    @endif

    {{-- Botão: Alterar imagem (visível quando existe imagem OU upload temporário) --}}
    @if ($mode === 'edit' || $existing)
        <button class="btn btn-info" type="button"
            onclick="document.getElementById('image_input_{{ $this->id() }}').click()">
            <i class="fa-solid fa-arrows-rotate"></i> Alterar Imagem
        </button>
    @endif

    {{-- Botão: Salvar imagem (somente modo edição) --}}
    @if ($mode === 'edit')
        <button class="btn btn-success" type="submit">
            <i class="fa-solid fa-floppy-disk"></i> Salvar Imagem
        </button>
    @endif

    @error($name)
        <p class="validation-fail-text text-xs">{{ Str::replace('.', '', $message) }}</p>
    @enderror

    {{-- Loading --}}
    <div wire:loading wire:target="image" class="mt-2 text-sm text-gray-500">
        <span class="loading loading-spinner loading-md"></span> Carregando imagem...
    </div>
</div>
