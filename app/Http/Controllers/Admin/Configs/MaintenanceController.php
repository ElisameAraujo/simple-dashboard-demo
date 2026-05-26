<?php

namespace App\Http\Controllers\Admin\Configs;

use App\Http\Controllers\Controller;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('admin.configs.maintenance.index');
    }
}
