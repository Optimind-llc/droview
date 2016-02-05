<?php

namespace App\Http\Requests\Api\Backend\Access\User;

use App\Http\Requests\Request;

/**
 * Class DeleteUserRequest
 * @package App\Http\Requests\Backend\Access\User
 */
class DeleteUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('permanently-delete-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filter'  => 'in:all,active,deactivated,deleted',
            'skip' => 'integer',
            'take'    => 'integer|min:1|max:50'
        ];
    }
}
