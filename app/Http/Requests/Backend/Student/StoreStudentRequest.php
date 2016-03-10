<?php

namespace App\Http\Requests\Backend\Student;

use App\Http\Requests\Request;

/**
 * Class StoreRoleRequest
 * @package App\Http\Requests\Backend\Access\Role
 */
class StoreStudentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-students');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'promotion_id' => 'required',
            'department_id' => 'required',
            'degree_id' => 'required',
            'grade_id' => 'required',
            'academic_year_id' => 'required',
            'name_latin' => 'required|max:255',
            'name_kh' => 'required|max:255',
            'observation' =>'max:255',
            'phone' => 'max:100',
            'email'=>'max:100',
            'address' => 'max:255',
            'address_current'=>'max:255',
            'parent_name' => 'max:255',
            'parent_address'=>'max:255',
            'gender_id' => 'required',
            'dob'=>'required',
            'address_current'=>'required',
            'origin_id' =>'required'
        ];
    }
}
