<?php

namespace App\Http\Requests\Backend\Exam;

use App\Http\Requests\Request;

/**
 * Class UpdateExamRequest
 * @package App\Http\Requests\Backend\Exam
 */
class UpdateExamRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-exams');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'date_start_end' => 'required',
            'academic_year_id' => 'required',
            'description' => 'max:255',
            'type_id' => 'required'
        ];
    }
}
