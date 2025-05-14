<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class ErbMapsController extends Controller
{
    public function index()
    {
        return view('tools.erb_maps_google');
    }
}
