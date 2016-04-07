<?php namespace App\Http\Requests\Backend\Course\CourseProgram;

use App\Http\Requests\Request;

/**
 * Class StoreRoleRequest
 * @package App\Http\Requests\Backend\Access\Role
 */
class ImportCourseProgramRequest extends Request
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
            'import' => 'required'
        ];
    }
}
