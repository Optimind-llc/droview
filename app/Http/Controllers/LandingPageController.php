<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * Class LandingPageController
 * @package App\Http\Controllers
 */
class LandingPageController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('landingPage.index');
    }
}
