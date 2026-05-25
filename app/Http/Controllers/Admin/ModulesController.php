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

        return view('admin.modules.show', compact('module'));
    }
}
