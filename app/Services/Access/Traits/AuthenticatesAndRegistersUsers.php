<?php

namespace App\Services\Access\Traits;

/**
 * Class AuthenticatesAndRegistersUsers
 * @package App\Services\Access\Traits
 */
trait AuthenticatesAndRegistersUsers
{
    use AuthenticatesUsers, RegistersUsers {
    	//同じディレクトリのAuthenticatesUsers
        AuthenticatesUsers::redirectPath insteadof RegistersUsers;
    }
}