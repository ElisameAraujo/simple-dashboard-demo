@extends('layouts.web')

@section('titulo', __('components/maintenance-mode.preview.title'))

@section('conteudo')
    <section class="web-preview-home">
        <span class="dashboard-kicker">{{ __('components/maintenance-mode.preview.kicker') }}</span>
        <div class="flex flex-col gap-3">
            <h1 class="font-unbounded text-4xl font-bold uppercase">
                {{ __('components/maintenance-mode.preview.heading') }}
            </h1>
            <p class="text-gray-500">
                {{ __('components/maintenance-mode.preview.description') }}
            </p>
        </div>

        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-base-100 p-4 shadow-sm">
            <p class="text-sm text-gray-500 dark:text-gray-300">
                {{ __('components/maintenance-mode.preview.note') }}
            </p>
        </div>
    </section>
@endsection
