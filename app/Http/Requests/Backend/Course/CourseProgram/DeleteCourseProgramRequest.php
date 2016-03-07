<?php

namespace App\Http\Requests\Backend\Configuration\CourseProgram;

use App\Http\Requests\Request;

/**
 * Class DeleteCourseProgramRequest
 * @package App\Http\Requests\Backend\Access\CourseProgram
 */
class DeleteCourseProgramRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-coursePrograms');
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
