<?php

namespace App\Http\Requests\Api\Backend\Flight;

use App\Http\Requests\Request;

/**
 * Class ActivateUserRequest
 * @package App\Http\Requests\Api\Backend\Flight
 */
class PlaceRequest extends Request
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

        ];
    }
}
