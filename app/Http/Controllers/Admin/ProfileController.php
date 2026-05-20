<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function myProfile()
    {
        return view('admin.profile.my-profile');
    }

    public function security()
    {
        return view('admin.profile.security');
    }

    public function notifications()
    {
        return view('admin.profile.notifications');
    }
}
