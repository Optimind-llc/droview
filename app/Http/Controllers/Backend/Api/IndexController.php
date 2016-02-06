<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
		$domain = env('APP_URL');
		$env = env('APP_ENV');
        return view('backend.single', compact('domain', 'env'));
    }
}
