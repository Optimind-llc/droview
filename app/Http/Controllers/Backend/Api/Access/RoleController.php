<?php

namespace App\Http\Controllers\Backend\Api\Access;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Http\Requests\Api\Backend\Access\Role\EditRoleRequest;
use App\Http\Requests\Api\Backend\Access\Role\StoreRoleRequest;
use App\Http\Requests\Api\Backend\Access\Role\CreateRoleRequest;
use App\Http\Requests\Api\Backend\Access\Role\DeleteRoleRequest;
use App\Http\Requests\Api\Backend\Access\Role\UpdateRoleRequest;
use App\Repositories\Backend\Permission\PermissionRepositoryContract;
use App\Repositories\Backend\Permission\Group\PermissionGroupRepositoryContract;

/**
 * Class RoleController
 * @package App\Http\Controllers\Access
 */
class RoleController extends Controller
{
    /**
     * @var RoleRepositoryContract
     */
    protected $roles;

    /**
     * @var PermissionRepositoryContract
     */
    protected $permissions;

    /**
     * @param RoleRepositoryContract       $roles
     * @param PermissionRepositoryContract $permissions
     */
    public function __construct(
        RoleRepositoryContract $roles,
        PermissionRepositoryContract $permissions
    )
    {
        $this->roles       = $roles;
        $this->permissions = $permissions;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return \Response::json($this->roles->getAllRoles());
    }

    /**
     * @param  PermissionGroupRepositoryContract $group
     * @param  CreateRoleRequest                 $request
     * @return mixed
     */
    public function create(PermissionGroupRepositoryContract $group, CreateRoleRequest $request)
    {
        return view('backend.access.roles.create')
            ->withGroups($group->getAllGroups())
            ->withPermissions($this->permissions->getUngroupedPermissions());
        // echo "<pre>";
        // var_dump($group->getAllGroups()->toArray());
        // echo "</pre>";
    }

    /**
     * @param  StoreRoleRequest $request
     * @return mixed
     */
    public function store(StoreRoleRequest $request)
    {
        $this->roles->create($request->all());
        return redirect()->route('admin.access.roles.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
    }

    /**
     * @param  $id
     * @param  PermissionGroupRepositoryContract $group
     * @param  EditRoleRequest                   $request
     * @return mixed
     */
    public function edit($id, PermissionGroupRepositoryContract $group, EditRoleRequest $request)
    {
        $role = $this->roles->findOrThrowException($id, true);
        return view('backend.access.roles.edit')
            ->withRole($role)
            ->withRolePermissions($role->permissions->lists('id')->all())
            ->withGroups($group->getAllGroups())
            ->withPermissions($this->permissions->getUngroupedPermissions());
    }

    /**
     * @param  $id
     * @param  UpdateRoleRequest $request
     * @return mixed
     */
    public function update($id, UpdateRoleRequest $request)
    {
        $this->roles->update($id, $request->all());
        return redirect()->route('admin.access.roles.index')->withFlashSuccess(trans('alerts.backend.roles.updated'));
    }

    /**
     * @param  $id
     * @param  DeleteRoleRequest $request
     * @return mixed
     */
    public function destroy(DeleteRoleRequest $request)
    {
        $this->roles->destroy($request->id);
        return \Response::json($this->roles->getAllRoles());
    }
}
