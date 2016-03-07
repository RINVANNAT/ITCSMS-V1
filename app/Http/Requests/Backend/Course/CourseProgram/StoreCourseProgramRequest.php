<?php

namespace App\Http\Requests\Backend\Configuration\CourseProgram;

use App\Http\Requests\Request;

/**
 * Class StoreCourseProgramRequest
 * @package App\Http\Requests\Backend\Configuration\CourseProgram
 */
class StoreCourseProgramRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-coursePrograms');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_en' => 'required|max:255',
            'name_kh' => 'max:255',
            'name_fr' => 'max:255',
            'code' => 'max:255',
            'time_tp' => 'required',
            'time_td' => 'required',
            'time_course' => 'required',
        ];
    }
}
