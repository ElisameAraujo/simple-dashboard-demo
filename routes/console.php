<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('demo:fresh {--force : Run the command in production}', function () {
    if (app()->isProduction() && ! $this->option('force')) {
        $this->components->error('The demo database cannot be refreshed in production without --force.');

        return self::FAILURE;
    }

    $connection = config('database.default');

    if ($connection === 'sqlite') {
        $database = config('database.connections.sqlite.database');

        if ($database !== ':memory:') {
            $databasePath = preg_match('/^[A-Za-z]:[\\\\\/]/', $database) === 1
                || str_starts_with($database, DIRECTORY_SEPARATOR)
                    ? $database
                    : base_path($database);

            File::ensureDirectoryExists(dirname($databasePath));

            if (! File::exists($databasePath)) {
                File::put($databasePath, '');
                $this->components->info("SQLite database created at {$databasePath}.");
            }
        }
    }

    $this->call('migrate:fresh', [
        '--seed' => true,
        '--force' => true,
    ]);

    if (File::exists(public_path('storage'))) {
        $this->components->info('The public storage link already exists.');
    } else {
        $this->call('storage:link');
    }

    $this->components->info('Demo environment refreshed.');

    return self::SUCCESS;
})->purpose('Create the SQLite database, refresh migrations, seed data, and link storage for the demo');
