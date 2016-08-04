<?php namespace App\Http\Requests\Backend\EntranceExamCourse;

use App\Http\Requests\Request;

/**
 * Class StoreEntranceExamCourseRequest
 * @package App\Http\Requests\Backend\EntranceExamCourse
 */
class StoreEntranceExamCourseRequest extends Request
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
            'name_en' => 'max:255',
            'name_fr' => 'max:255',
            'total_question' => 'integer',
            'description' => 'max:255',
            'exam_id' => 'required'
        ];
    }
}
