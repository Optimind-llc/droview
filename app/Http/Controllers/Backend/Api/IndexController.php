<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
		$domain = env('APP_URL');
        return view('backend.single', compact('domain'));
    }
}
