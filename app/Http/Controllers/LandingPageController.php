<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Jobs\SendContactEmail;

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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact(ContactRequest $request)
    {
    	$name = $request->name;
    	$email = $request->email;
    	$comments = $request->comments;

        $file = $name . $email . $comments . '.txt';
        $string = $name . $email . $comments;

        file_put_contents('/app/web/storage/'.$file , $string);
        //$this->dispatch(new SendContactEmail($name, $email, $comments));
    	return view('landingPage.contact',compact('name', 'email', 'comments'));
    }
}
