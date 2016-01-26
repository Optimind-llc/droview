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
use App\Http\Requests\Api\Backend\Access\User\MarkUserRequest;
use App\Http\Requests\Api\Backend\Access\User\UpdateUserRequest;
use App\Http\Requests\Api\Backend\Access\User\DeleteUserRequest;
use App\Http\Requests\Api\Backend\Access\User\RestoreUserRequest;
use App\Http\Requests\Api\Backend\Access\User\ChangeUserPasswordRequest;
use App\Http\Requests\Api\Backend\Access\User\UpdateUserPasswordRequest;
use App\Repositories\Backend\Permission\PermissionRepositoryContract;
use App\Http\Requests\Api\Backend\Access\User\PermanentlyDeleteUserRequest;
use App\Repositories\Frontend\User\UserContract as FrontendUserContract;
use App\Http\Requests\Api\Backend\Access\User\ResendConfirmationEmailRequest;
use App\Models\Access\User\User;
use App\Models\Access\Role\Role;

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

    protected function getUsersPaginated($request)
    {
        return $this->users->getCustomUsersPaginated(
            $request->filter,
            $request->skip,
            $request->take
        );
    }

    /**
     * @return mixed
     */
    public function users(ShowUsersRequest $request)
    {
        return \Response::json($this->getUsersPaginated($request));
    }

    /**
     * @return mixed
     */
    public function user(ShowUserRequest $request)
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
        return \Response::json('success');
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

    /**
     * @param  $id
     * @param  DeleteUserRequest $request
     * @return mixed
     */
    public function delete(DeleteUserRequest $request)
    {
        $this->users->destroy($request->id);
        return \Response::json($this->getUsersPaginated($request));
    }

    /**
     * @param  $id
     * @param  PermanentlyDeleteUserRequest $request
     * @return mixed
     */
    public function permanentlyDelete(PermanentlyDeleteUserRequest $request)
    {
        $this->users->delete($request->id);
        return \Response::json($this->getUsersPaginated($request));
    }

    /**
     * @param  $id
     * @param  RestoreUserRequest $request
     * @return mixed
     */
    public function restore(RestoreUserRequest $request)
    {
        $this->users->restore($request->id);
        return \Response::json($this->getUsersPaginated($request));
    }

    /**
     * @param  $id
     * @param  $status
     * @param  MarkUserRequest $request
     * @return mixed
     */
    public function mark(MarkUserRequest $request)
    {
        $this->users->mark($request->id, $request->status);
        return \Response::json($this->getUsersPaginated($request));
    }

    /**
     * @param  $id
     * @param  UpdateUserPasswordRequest $request
     * @return mixed
     */
    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $this->users->updatePassword($request->id, $request->all());
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
        $user->sendConfirmationEmail($user_id);
        return redirect()->back()->withFlashSuccess(trans('alerts.backend.users.confirmation_email'));
    }
}