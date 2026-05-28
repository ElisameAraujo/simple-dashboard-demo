<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SearchDemoController extends Controller
{
    public function editPost(string $post)
    {
        return view('admin.search.demo-edit', [
            'type' => __('components/search-engine.demo_edit.posts.type'),
            'title' => __('components/search-engine.demo_edit.posts.title'),
            'description' => __('components/search-engine.demo_edit.posts.description'),
            'identifier' => $post,
        ]);
    }

    public function editProduct(string $product)
    {
        return view('admin.search.demo-edit', [
            'type' => __('components/search-engine.demo_edit.products.type'),
            'title' => __('components/search-engine.demo_edit.products.title'),
            'description' => __('components/search-engine.demo_edit.products.description'),
            'identifier' => $product,
        ]);
    }
}
