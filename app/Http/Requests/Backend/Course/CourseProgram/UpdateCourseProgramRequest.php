<?php namespace App\Http\Requests\Backend\Course\CourseProgram;

use App\Http\Requests\Request;

/**
 * Class UpdateCourseProgramRequest
 * @package App\Http\Requests\Backend\Configuration\CourseProgram
 */
class UpdateCourseProgramRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-coursePrograms');
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
            'name_kh' => 'required|max:255',
            'name_fr' => 'required|max:255',
            'code' => 'max:255',
            'time_tp' => 'required',
            'time_td' => 'required',
            'time_course' => 'required',
            'credit' => 'required',
            'degree_id' => 'required',
            'grade_id' => 'required',
            'department_id' => 'required',
            'semester_id' => 'required'
        ];
    }
}
