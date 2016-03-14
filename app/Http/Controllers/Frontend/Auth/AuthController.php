<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Services\Access\Traits\ConfirmUsers;
use App\Services\Access\Traits\UseSocialite;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Repositories\Frontend\User\UserContract;
use App\Services\Access\Traits\AuthenticatesAndRegistersUsers;

/**
 * Class AuthController
 * @package App\Http\Controllers\Frontend\Auth
 */
class AuthController extends Controller
{

    use AuthenticatesAndRegistersUsers, ConfirmUsers, ThrottlesLogins, UseSocialite;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/droview/reserved';

    /**
     * Where to redirect Admin user after login.
     *
     * @var string
     */
    protected $adminRedirectTo = '/admin/single';

    /**
     * Where to redirect users after they logout
     *
     * @var string
     */
    protected $redirectAfterLogout = '/droview';

    /**
     * @param UserContract $user
     */
    public function __construct(UserContract $user)
    {
        $this->user = $user;
    }

    /**
     * ユーザーロールによってログイン後のリダイレクト先を変更
     * Override following class method.
     * vendor/laravel/framework/src/Illuminate/Foundation/Auth/RedirectsUsers.php
     *
     * @var string
     */
    public function redirectPath()
    {
        // if (property_exists($this, 'redirectPath')) {
        //     return $this->redirectPath;
        // }

        // return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';

        $user = \Auth::user();
        if ($user->hasRole('Administrator'))
        {
           return property_exists($this, 'adminRedirectTo') ? $this->adminRedirectTo : '/home';
        
        } else
        {
           return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';            
        }
    }
}