<?php

namespace App\Http\Controllers\Backend\Api\Access;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\User\UserContract;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Http\Requests\Api\Backend\Access\User\ShowUsersRequest;
use App\Http\Requests\Api\Backend\Access\User\ShowUserRequest;
use App\Http\Requests\Api\Backend\Access\User\CreateUserRequest;
use App\Http\Requests\Api\Backend\Access\User\StoreUserRequest;
use App\Http\Requests\Api\Backend\Access\User\EditUserRequest;
use App\Http\Requests\Api\Backend\Access\User\UpdateUserRequest;
use App\Http\Requests\Api\Backend\Access\User\ActivateUserRequest;
use App\Http\Requests\Api\Backend\Access\User\DeactivateUserRequest;
use App\Http\Requests\Api\Backend\Access\User\RestoreUserRequest;
use App\Http\Requests\Api\Backend\Access\User\DestroyUserRequest;
use App\Http\Requests\Api\Backend\Access\User\DeleteUserRequest;
use App\Http\Requests\Api\Backend\Access\User\ChangeUserPasswordRequest;
use App\Http\Requests\Api\Backend\Access\User\UpdateUserPasswordRequest;
use App\Repositories\Backend\Permission\PermissionRepositoryContract;
use App\Http\Requests\Api\Backend\Access\User\PermanentlyDeleteUserRequest;
use App\Repositories\Frontend\User\UserContract as FrontendUserContract;
use App\Http\Requests\Api\Backend\Access\User\ResendConfirmationEmailRequest;
use App\Models\Access\User\User;
use App\Models\Access\Role\Role;
use App\Exceptions\GeneralException;
//Jobs
use App\Jobs\SendConfirmationEmail;

/**
 * Class UserController
 */
class UserController extends Controller
{
    /**
     * @var UserContract
     */
    protected $users;

    /**
     * @var RoleRepositoryContract
     */
    protected $roles;

    /**
     * @var PermissionRepositoryContract
     */
    protected $permissions;

    /**
     * @param UserContract                 $users
     * @param RoleRepositoryContract       $roles
     * @param PermissionRepositoryContract $permissions
     */
    public function __construct(
        UserContract $users,
        RoleRepositoryContract $roles,
        PermissionRepositoryContract $permissions
    )
    {
        $this->users       = $users;
        $this->roles       = $roles;
        $this->permissions = $permissions;
    }

    /**
     * @return mixed
     */
    public function index(ShowUsersRequest $request)
    {
        return \Response::json($this->getUsersPaginated($request));
    }

    /**
     * @return mixed
     */
    public function show($id, ShowUserRequest $request)
    {
        $user = $this->users->findOrThrowException($request->id, true);
        $user->roles;
        return \Response::json($user);
    }

    /**
     * @param  StoreUserRequest $request
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        $this->users->create(
            $request->except('assignees_roles', 'permission_user', 'q'),
            $request->only('assignees_roles'),
            $request->only('permission_user')
        );

        //return \Response::json('success', 200);
        return \Response::json($this->users->getNumberOfUsers(), 200);
    }

    /**
     * @param  $id
     * @param  UpdateUserRequest $request
     * @return mixed
     */
    public function update(UpdateUserRequest $request)
    {
        $this->users->update($request->id,
            $request->except('assignees_roles', 'permission_user', 'q', 'roles'),
            $request->only('assignees_roles'),
            $request->only('permission_user')
        );
        return \Response::json('success');
    }

    public function activate($id, ActivateUserRequest $request)
    {
        $this->users->mark($id, 1);
        return \Response::json($this->getUsersPaginated($request));
    }

    public function deactivate($id, DeactivateUserRequest $request)
    {
        $this->users->mark($id, 0);
        return \Response::json($this->getUsersPaginated($request));
    }

    public function restore($id, RestoreUserRequest $request)
    {
        $this->users->restore($id);
        return \Response::json($this->getUsersPaginated($request));
    }

    public function destroy($id, DestroyUserRequest $request)
    {
        $this->users->destroy($request->id);
        return \Response::json($this->getUsersPaginated($request), 200);
    }

    public function delete($id, DeleteUserRequest $request)
    {
        $this->users->delete($id);
        return \Response::json($this->getUsersPaginated($request), 200);
    }

    /**
     * @param  $id
     * @param  UpdateUserPasswordRequest $request
     * @return mixed
     */
    public function updatePassword($id, UpdateUserPasswordRequest $request)
    {
        $this->users->updatePassword($id, $request->all());
        return \Response::json('success');
    }

    /**
     * @param  $user_id
     * @param  FrontendUserContract $user
     * @param  ResendConfirmationEmailRequest $request
     * @return mixed
     */
    public function resendConfirmationEmail($user_id, FrontendUserContract $user, ResendConfirmationEmailRequest $request)
    {
        //Queue jobを使ってメール送信
        $this->dispatch(new SendConfirmationEmail($user->find($user_id)));
        return \Response::json($this->getUsersPaginated($request), 200);
    }

    protected function getUsersPaginated($request)
    {
        return $this->users->getCustomUsersPaginated(
            $request->filter == null ? 'all' : $request->filter,
            $request->skip == null ? '0' : $request->skip,
            $request->take == null ? '10' : $request->take
        );
    }
}