<?php

namespace App\Http\Requests\Backend\Configuration\DepartmentOption;

use App\Http\Requests\Request;

/**
 * Class StoreDepartmentOptionRequest
 * @package App\Http\Requests\Backend\Configuration\DepartmentOption
 */
class StoreDepartmentOptionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-departmentOptions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:10|unique:rooms',
            'room_type_id' => 'required',
            'building_id' => 'required',
            'department_id' => 'required',
        ];
    }
}
