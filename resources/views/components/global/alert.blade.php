@props([
    'type' => 'info', // success, info, error, warning
    'message' => '',
    'timeout' => 5000, // ms
])

<div
    {{ $attributes->merge([
        'class' => 'alert '.($type === 'draft' ? '' : 'alert-'.$type).' col-span-12 mb-2 relative',
        'role' => 'alert',
    ]) }}
    x-data="{ show: true }" x-init="setTimeout(() => show = false, {{ $timeout }})" x-show="show"
    x-transition.opacity.duration.500ms>
    {{-- Ícones automáticos por tipo --}}
    @if ($type === 'success')
        <i class="fa-regular fa-circle-check"></i>
    @elseif ($type === 'info')
        <i class="fa-solid fa-circle-info"></i>
    @elseif ($type === 'error')
        <i class="fa-regular fa-circle-xmark"></i>
    @elseif ($type === 'draft')
        <i class="fa-solid fa-file-pen"></i>
    @elseif ($type === 'warning')
        <i class="fa-solid fa-ban"></i>
    @elseif ($type === 'deleted')
        <i class="fa-regular fa-trash-can"></i>
    @endif

    <p class="flex-1">{!! $message !!}</p>
</div>
