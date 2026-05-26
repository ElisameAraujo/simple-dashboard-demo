@if (session('success'))
    <x-global.alert type="success" :message="session('success')" wire:key="flash-success-{{ \Illuminate\Support\Str::uuid() }}" />
@endif

@if (session('updated'))
    <x-global.alert type="info" :message="session('updated')" wire:key="flash-updated-{{ \Illuminate\Support\Str::uuid() }}" />
@endif

@if (session('deleted'))
    <x-global.alert type="deleted" :message="session('deleted')" wire:key="flash-deleted-{{ \Illuminate\Support\Str::uuid() }}" />
@endif

@if (session('error'))
    <x-global.alert type="error" :message="session('error')" wire:key="flash-error-{{ \Illuminate\Support\Str::uuid() }}" />
@endif

@if (session('draft'))
    <x-global.alert type="draft" :message="session('draft')" wire:key="flash-draft-{{ \Illuminate\Support\Str::uuid() }}" />
@endif

@if (session('warning'))
    <x-global.alert type="warning" :message="session('warning')" wire:key="flash-warning-{{ \Illuminate\Support\Str::uuid() }}" />
@endif
