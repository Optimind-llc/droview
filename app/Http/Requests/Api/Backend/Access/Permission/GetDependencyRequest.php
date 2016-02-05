<?php

namespace App\Http\Requests\Api\Backend\Access\Permission;

use App\Http\Requests\Request;

/**
 * Class GetDependencyRequest
 * @package App\Http\Requests\Backend\Access\Permission
 */
class GetDependencyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
