<?php namespace App\Http\Requests\Backend\Course\CourseSession;

use App\Http\Requests\Request;

/**
 * Class EditCourseSessionRequest
 * @package App\Http\Requests\Backend\Configuration\CourseSession
 */
class EditCourseSessionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-courseSessions');
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
