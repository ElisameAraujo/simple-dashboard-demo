@extends('errors.minimal')

@php
    $message = __('components/maintenance-mode.message.default');

    try {
        $message = \App\Models\Configs\MaintenanceSetting::current()->customMaintenanceMessage() ?? $message;
    } catch (\Throwable) {
        //
    }
@endphp

@section('title', __('components/maintenance-mode.status.down'))
@section('code', '503')
@section('message', e($message))
@section('error-image')
    <img class="img-error" src="{{ asset('img/svg/error-503.svg') }}" alt="Em Manutenção">
@endsection
