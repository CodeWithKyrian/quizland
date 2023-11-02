<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard');
    }
}
