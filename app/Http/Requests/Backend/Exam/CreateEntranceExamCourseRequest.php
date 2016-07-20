<?php

namespace App\Http\Requests\Backend\Exam;

use App\Http\Requests\Request;

/**
 * Class CreateEntranceExamCourseRequest
 * @package App\Http\Requests\Backend\Exam
 */
class CreateEntranceExamCourseRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-entrance-exam-courses');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_kh' => 'required|max:255',
            'description' => 'max:255',
            'total_question' => 'integer',
        ];
    }
}
