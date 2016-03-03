<?php

namespace App\Http\Requests\Backend\Configuration\StudentBac2;

use App\Http\Requests\Request;

/**
 * Class EditStudentBac2Request
 * @package App\Http\Requests\Backend\Configuration\StudentBac2
 */
class EditStudentBac2Request extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-studentBac2s');
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
