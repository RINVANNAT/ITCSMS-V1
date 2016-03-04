<?php

namespace App\Http\Requests\Backend\Configuration\StudentBac2;

use App\Http\Requests\Request;

/**
 * Class CreateStudentBac2Request
 * @package App\Http\Requests\Backend\Configuration\StudentBac2
 */
class CreateStudentBac2Request extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
