<?php

namespace App\Http\Requests\Backend\Exam;

use App\Http\Requests\Request;

/**
 * Class ViewExamRequest
 * @package App\Http\Requests\Backend\Exam
 */
class ViewExamRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('view-exam-management');
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
