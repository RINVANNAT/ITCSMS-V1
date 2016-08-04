<?php

namespace App\Http\Requests\Backend\EntranceExamCourse;

use App\Http\Requests\Request;

/**
 * Class DeleteEntranceExamCourseRequest
 * @package App\Http\Requests\Backend\EntranceExamCourse
 */
class DeleteEntranceExamCourseRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-entrance-exam-courses');
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
