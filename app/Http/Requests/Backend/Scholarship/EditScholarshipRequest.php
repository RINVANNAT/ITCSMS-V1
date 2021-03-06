<?php

namespace App\Http\Requests\Backend\Scholarship;

use App\Http\Requests\Request;

/**
 * Class EditScholarshipRequest
 * @package App\Http\Requests\Backend\Scholarship
 */
class EditScholarshipRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-scholarships');
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
