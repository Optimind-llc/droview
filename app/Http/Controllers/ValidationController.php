<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Validation\ValidateUserRequest;
use App\Http\Requests\Validation\ValidateRoleRequest;
use App\Http\Requests\Validation\GetAddressRequest;
use App\Repositories\Frontend\User\UserContract;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Exceptions\NotFoundException;

/**
 * Class IndexController
 * @package App\Http\Controllers
 */
class ValidationController extends Controller
{
    protected $users;
    protected $roles;

    public function __construct(
        UserContract $users,
        RoleRepositoryContract $roles
    )
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    public function getAddress($post1, $post2, GetAddressRequest $request)
	{
		$html = @file_get_contents('http://api.thni.net/jzip/X0401/JSON/' . $post1 . '/' . $post2 . '.js');
		
		if($http_response_header[0] == 'HTTP/1.1 404 Not Found'){
			throw new NotFoundException('server.users.notFound');
		}

	    return \Response::json(json_decode($html));
	}

    public function User(ValidateUserRequest $request)
    {
        if ($this->users->findByEmail($request->email)) {
            return \Response::json(['mail' => 'sample varidate']);
        }

        return \Response::json('ok');
    }

    public function Role(ValidateRoleRequest $request)
    {
        if ($this->roles->findByName($request->name)) {
            return \Response::json(['mail' => 'sample varidate']);
        }

        return \Response::json('ok');
    }
}