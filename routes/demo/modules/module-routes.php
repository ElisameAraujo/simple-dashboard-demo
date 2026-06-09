<?php

use App\Http\Controllers\Admin\ModulesController;
use App\Http\Controllers\Admin\Media\RichTextMediaUploadController;
use App\Http\Controllers\Admin\SearchDemoController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/rich-text-media')->group(function () {
    Route::post('uploads', [RichTextMediaUploadController::class, 'store'])
        ->name('admin.rich-text-media.uploads.store');
});

Route::prefix('modules')->group(function () {
    Route::get('search-engine/demo/posts/{post}/edit', [SearchDemoController::class, 'editPost'])
        ->name('search.demo.posts.edit');

    Route::get('search-engine/demo/products/{product}/edit', [SearchDemoController::class, 'editProduct'])
        ->name('search.demo.products.edit');

    Route::get('search-engine/{section}', [ModulesController::class, 'showSearchEngineSection'])
        ->name('modules.search-engine.section');

    Route::get('{module}/{section}', [ModulesController::class, 'showSection'])
        ->name('modules.section');

    Route::get('', [ModulesController::class, 'index'])->name('modules.index');
    Route::get('{module}', [ModulesController::class, 'show'])->name('modules.show');
});

Route::view('site-preview/posts/{post}', 'web.site-preview')
    ->name('search.demo.posts.show');

Route::view('site-preview/products/{product}', 'web.site-preview')
    ->name('search.demo.products.show');
