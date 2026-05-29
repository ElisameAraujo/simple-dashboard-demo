<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ModuleDemoCatalog;

class ModulesController extends Controller
{
    public function index()
    {
        $modules = ModuleDemoCatalog::all();

        return view('admin.modules.index', compact('modules'));
    }

    public function show(string $module)
    {
        $module = ModuleDemoCatalog::find($module);

        abort_unless($module, 404);

        $selectedSection = $module['sections'][0] ?? null;

        return view('admin.modules.show', compact('module', 'selectedSection'));
    }

    public function showSearchEngineSection(string $section)
    {
        $module = ModuleDemoCatalog::find('search-engine');

        abort_unless($module, 404);

        $selectedSection = collect($module['sections'])
            ->firstWhere('id', $section);

        abort_unless($selectedSection, 404);

        return view('admin.modules.show', compact('module', 'selectedSection'));
    }
}
