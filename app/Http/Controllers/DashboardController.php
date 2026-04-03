<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'memberCount' => Member::count(),
            'adminCount' => User::where('role', 'admin')->count(),
        ]);
    }
}