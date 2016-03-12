<?php

namespace App\Http\Requests\Backend\Configuration\DepartmentOption;

use App\Http\Requests\Request;

/**
 * Class DeleteDepartmentOptionRequest
 * @package App\Http\Requests\Backend\Configuration\DepartmentOption
 */
class DeleteDepartmentOptionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-departmentOptions');
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
