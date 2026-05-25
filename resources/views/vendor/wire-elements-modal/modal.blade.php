<div>
    @isset($jsPath)
        <script>
            {!! file_get_contents($jsPath) !!}
        </script>
    @endisset
    @isset($cssPath)
        <style>
            {!! file_get_contents($cssPath) !!}
        </style>
    @endisset

    <div x-data="LivewireUIModal()" x-on:close.stop="setShowPropertyTo(false)"
        x-on:keydown.escape.window="show && closeModalOnEscape()" x-show="show"
        class="fixed inset-0 z-50 overflow-y-auto font-poppins" style="display: none;">
        <div class="relative flex min-h-dvh items-center justify-center p-4 text-center">
            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 cursor-pointer bg-black/60 backdrop-blur-sm transition-opacity"
                x-on:click="closeModalOnClickAway()"></div>

            <div x-show="show && showActiveComponent" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-bind:class="modalWidth"
                class="relative z-10 flex max-h-[90dvh] w-[calc(100vw-2rem)] max-w-4xl transform flex-col overflow-hidden rounded-lg border border-gray-200 bg-base-100 p-0 text-left shadow-xl transition-all"
                id="modal-container" x-trap.noscroll.inert="show && showActiveComponent" aria-modal="true">
                @forelse ($components as $id => $component)
                    <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}"
                        wire:key="{{ $id }}">
                        @livewire($component['name'], $component['arguments'], key($id))
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
