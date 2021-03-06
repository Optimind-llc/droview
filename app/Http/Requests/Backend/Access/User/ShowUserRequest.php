<?php

namespace App\Http\Requests\Backend\Access\User;

use App\Http\Requests\Request;

/**
 * Class CreateUserRequest
 * @package App\Http\Requests\Backend\Access\User
 */
class ShowUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('view-access-management');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filter'  => 'required',
            'skip' => 'required|integer',
            'take'    => 'required|integer|min:2|max:50',
        ];
    }
}
