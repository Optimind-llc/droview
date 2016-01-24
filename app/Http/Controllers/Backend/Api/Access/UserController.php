<?php

namespace App\Http\Controllers\Backend\Api\Access;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\User\UserContract;
use App\Repositories\Backend\Role\RoleRepositoryContract;
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
    public function index(ShowUserRequest $request)
    {
        return \Response::json($this->getUsersPaginated($request));
    }

    /**
     * @param  CreateUserRequest $request
     * @return mixed
     */
    public function create(CreateUserRequest $request)
    {
        return view('backend.access.create')
            ->withRoles($this->roles->getAllRoles('sort', 'asc', true))
            ->withPermissions($this->permissions->getAllPermissions());
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
        return \Response::json(['success' => 'alert.access.users.storeSuccess']);
    }

    /**
     * @param  $id
     * @param  EditUserRequest $request
     * @return mixed
     */
    public function edit($id, EditUserRequest $request)
    {
        $user = $this->users->findOrThrowException($id, true);
        return view('backend.access.edit')
            ->withUser($user)
            ->withUserRoles($user->roles->lists('id')->all())
            ->withRoles($this->roles->getAllRoles('sort', 'asc', true))
            ->withUserPermissions($user->permissions->lists('id')->all())
            ->withPermissions($this->permissions->getAllPermissions());
    }

    /**
     * @param  $id
     * @param  UpdateUserRequest $request
     * @return mixed
     */
    public function update($id, UpdateUserRequest $request)
    {
        $this->users->update($id,
            $request->except('assignees_roles', 'permission_user', 'q'),
            $request->only('assignees_roles'),
            $request->only('permission_user')
        );
        return redirect()->route('admin.access.users.index')->withFlashSuccess(trans('alerts.backend.users.updated'));
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
     * @param  ChangeUserPasswordRequest $request
     * @return mixed
     */
    public function changePassword($id, ChangeUserPasswordRequest $request)
    {
        return view('backend.access.change-password')
            ->withUser($this->users->findOrThrowException($id));
    }

    /**
     * @param  $id
     * @param  UpdateUserPasswordRequest $request
     * @return mixed
     */
    public function updatePassword($id, UpdateUserPasswordRequest $request)
    {
        $this->users->updatePassword($id, $request->all());
        return redirect()->route('admin.access.users.index')->withFlashSuccess(trans('alerts.backend.users.updated_password'));
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

    public function getAllRoles() {
        $roles = Role::all(['id', 'name']);
        return \Response::json($roles);
    }
}