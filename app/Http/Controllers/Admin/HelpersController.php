<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\HelperDemoCatalog;

class HelpersController extends Controller
{
    public function index()
    {
        $helpers = HelperDemoCatalog::all();

        return view('admin.helpers.index', compact('helpers'));
    }

    public function show(string $helper)
    {
        $helper = HelperDemoCatalog::find($helper);

        abort_unless($helper, 404);

        return view('admin.helpers.show', compact('helper'));
    }
}
