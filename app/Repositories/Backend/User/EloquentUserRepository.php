<?php

namespace App\Repositories\Backend\User;

use App\Models\Access\User\User;
use App\Exceptions\GeneralException;
use App\Exceptions\NotFoundException;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Exceptions\Backend\Access\User\UserNeedsRolesException;
use App\Repositories\Frontend\User\UserContract as FrontendUserContract;

/**
 * Class EloquentUserRepository
 * @package App\Repositories\User
 */
class EloquentUserRepository implements UserContract
{
    /**
     * @var RoleRepositoryContract
     */
    protected $role;

    /**
     * @var FrontendUserContract
     */
    protected $user;

    /**
     * @param RoleRepositoryContract $role
     * @param FrontendUserContract $user
     */
    public function __construct(
        RoleRepositoryContract $role,
        FrontendUserContract $user
    )
    {
        $this->role = $role;
        $this->user = $user;
    }

    /**
     * @param  $id
     * @param  bool               $withRoles
     * @throws GeneralException
     * @return mixed
     */
    public function findOrThrowException($id, $withRoles = false)
    {
        if ($withRoles) {
            $user = User::with('roles')->withTrashed()->find($id);
        } else {
            $user = User::withTrashed()->find($id);
        }

        if (!is_null($user)) {
            return $user;
        }

        throw new NotFoundException('server.users.notFound');
        throw new GeneralException(trans('exceptions.backend.access.users.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @param  int         $status
     * @return mixed
     */
    public function getUsersPaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc')
    {
        return User::where('status', $status)
            ->orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    protected function hasPermissions($user) {
        $permissions = array();
        if ($user->permissions()->count() > 0) {
            foreach ($user->permissions as $permission) {
                $permissions[] = $permission;
            }
        }
        return $permissions;
    }

    public function getNumberOfUsers() {
        return User::all()->count();
    }

    public function getCustomUsersPaginated($filter = 'all', $skip = '0', $take = '10', $order_by = 'id', $sort = 'asc')
    {
        $total = 0;
        $users = null;
        switch ($filter){
        case 'all':
            $total = User::all()->count();
            $users = User::skip($skip)->take($take)->with('roles')->get(
                ['id', 'user_id', 'name','email','status','confirmed','created_at','updated_at','deleted_at']
            );
          break;
        case 'active':
            $total = User::where('status', '=', '1')->count();
            $users = User::where('status', '=', '1')->skip($skip)->take($take)->with('roles')->get(
                ['id', 'user_id', 'name','email','status','confirmed','created_at','updated_at','deleted_at']
            );
          break;
        case 'deactivated':
            $total = User::where('status', '=', '0')->count();
            $users = User::where('status', '=', '0')->skip($skip)->take($take)->with('roles')->get(
                ['id', 'user_id', 'name','email','status','confirmed','created_at','updated_at','deleted_at']
            );
          break;
        case 'deleted':
            $total = User::onlyTrashed()->count();
            $users = User::onlyTrashed()->skip($skip)->take($take)->with('roles')->get(
                ['id', 'user_id', 'name','email','status','confirmed','created_at','updated_at','deleted_at']
            );
          break;
        default:
            throw new NotFoundException('server.users.notFound');
        }

        return $result = array('users' => $users, 'total' => $total);
    }

    /**
     * @param  $per_page
     * @return \Illuminate\Pagination\Paginator
     */
    public function getDeletedUsersPaginated($per_page)
    {
        return User::onlyTrashed()
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllUsers($order_by = 'id', $sort = 'asc')
    {
        return User::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @param  $roles
     * @param  $permissions
     * @throws GeneralException
     * @throws UserNeedsRolesException
     * @return bool
     */
    public function create($input, $roles, $permissions)
    {
        $user = $this->createUserStub($input);

        if ($user->save()) {
            //User Created, Validate Roles
            $this->validateRoleAmount($user, $roles['assignees_roles']);

            //Attach new roles
            $user->attachRoles($roles['assignees_roles']);

            //Attach other permissions
            $user->attachPermissions($permissions['permission_user']);

            //Send confirmation email if requested
            if (isset($input['confirmation_email']) && $user->confirmed == 0) {
                $this->user->sendConfirmationEmail($user->id);
            }

            return true;
        }

        throw new ApiException(trans('alert.users.createError'));
        throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
    }

    /**
     * @param $id
     * @param $input
     * @param $roles
     * @param $permissions
     * @return bool
     * @throws GeneralException
     */
    public function update($id, $input, $roles, $permissions)
    {
        $user = $this->findOrThrowException($id);
        $this->checkUserByEmail($input, $user);

        if ($user->update($input)) {
            //For whatever reason this just wont work in the above call, so a second is needed for now
            $user->status    = isset($input['status']) ? $input['status'] : 0;
            $user->confirmed = isset($input['confirmed']) ? $input['status'] : 0;
            $user->save();

            $this->checkUserRolesCount($roles);
            $this->flushRoles($roles, $user);
            $this->flushPermissions($permissions, $user);

            return true;
        }

        throw new ApiException(trans('alert.users.updateError'));
        throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function updatePassword($id, $input)
    {
        $user = $this->findOrThrowException($id);

        //Passwords are hashed on the model
        $user->password = $input['password'];
        if ($user->save()) {
            return true;
        }

        throw new ApiException('alert.access.users.updatePasswordError');
        throw new GeneralException(trans('exceptions.backend.access.users.update_password_error'));
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {
        if (auth()->id() == $id) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_self'));
        }

        $user = $this->findOrThrowException($id);
        if ($user->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return boolean|null
     */
    public function delete($id)
    {
        $user = $this->findOrThrowException($id, true);

        //Detach all roles & permissions
        $user->detachRoles($user->roles);
        $user->detachPermissions($user->permissions);

        try {
            $user->forceDelete();
        } catch (\Exception $e) {
            throw new GeneralException($e->getMessage());
        }
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function restore($id)
    {
        $user = $this->findOrThrowException($id);

        if ($user->restore()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.restore_error'));
    }

    /**
     * @param  $id
     * @param  $status
     * @throws GeneralException
     * @return bool
     */
    public function mark($id, $status)
    {
        if (access()->id() == $id && $status == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_deactivate_self'));
        }

        $user         = $this->findOrThrowException($id);
        $user->status = $status;

        if ($user->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.mark_error'));
    }

    /**
     * Check to make sure at lease one role is being applied or deactivate user
     *
     * @param  $user
     * @param  $roles
     * @throws UserNeedsRolesException
     */
    private function validateRoleAmount($user, $roles)
    {
        //Validate that there's at least one role chosen, placing this here so
        //at lease the user can be updated first, if this fails the roles will be
        //kept the same as before the user was updated
        if (count($roles) == 0) {
            //Deactivate user
            $user->status = 0;
            $user->save();

            $exception = new UserNeedsRolesException();
            $exception->setValidationErrors(trans('exceptions.backend.access.users.role_needed_create'));

            //Grab the user id in the controller
            $exception->setUserID($user->id);
            throw $exception;
        }
    }

    /**
     * @param  $input
     * @param  $user
     * @throws GeneralException
     */
    private function checkUserByEmail($input, $user)
    {
        //Figure out if email is not the same
        if ($user->email != $input['email']) {
            //Check to see if email exists
            if (User::where('email', '=', $input['email'])->first()) {
                throw new GeneralException(trans('exceptions.backend.access.users.email_error'));
            }

        }
    }

    /**
     * @param $roles
     * @param $user
     */
    private function flushRoles($roles, $user)
    {
        //Flush roles out, then add array of new ones
        $user->detachRoles($user->roles);
        $user->attachRoles($roles['assignees_roles']);
    }

    /**
     * @param $permissions
     * @param $user
     */
    private function flushPermissions($permissions, $user)
    {
        //Flush permissions out, then add array of new ones if any
        $user->detachPermissions($user->permissions);
        if (count($permissions['permission_user']) > 0) {
            $user->attachPermissions($permissions['permission_user']);
        }

    }

    /**
     * @param  $roles
     * @throws GeneralException
     */
    private function checkUserRolesCount($roles)
    {
        //User Updated, Update Roles
        //Validate that there's at least one role chosen
        if (count($roles['assignees_roles']) == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.role_needed'));
        }

    }

    /**
     * @param  $input
     * @return mixed
     */
    private function createUserStub($input)
    {
        $user                    = new User;
        $user->name              = isset($input['name']) ? $input['name'] : $input['user_id'];
        $user->user_id           = isset($input['user_id']) ? $input['user_id'] : $input['name'];
        $user->email             = $input['email'];
        $user->password          = bcrypt($input['password']);
        $user->first_name        = isset($input['first_name']) ? $input['first_name'] : null;
        $user->last_name         = isset($input['last_name']) ? $input['last_name'] : null;
        $user->age               = isset($input['age']) ? $input['age'] : 0;
        $user->sex               = isset($input['sex']) ? $input['sex'] : 0;
        $user->postal_code       = isset($input['postal_code']) ? $input['postal_code'] : 0;
        $user->state             = isset($input['state']) ? $input['state'] : "";
        $user->city              = isset($input['city']) ? $input['city'] : "";
        $user->street            = isset($input['street']) ? $input['street'] : "";
        $user->building          = isset($input['building']) ? $input['building'] : "";
        $user->status            = isset($input['status']) ? $input['status'] : 0;
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed         = isset($input['confirmed']) ? $input['status'] : 0;
        return $user;
    }
}
