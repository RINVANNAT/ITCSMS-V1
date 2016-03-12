<?php

namespace App\Http\Requests\Backend\Configuration\DepartmentOption;

use App\Http\Requests\Request;

/**
 * Class UpdateDepartmentOptionRequest
 * @package App\Http\Requests\Backend\Configuration\DepartmentOption
 */
class UpdateDepartmentOptionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-departmentOptions');
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
