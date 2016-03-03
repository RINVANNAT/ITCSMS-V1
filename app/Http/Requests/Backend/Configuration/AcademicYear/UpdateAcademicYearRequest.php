<?php

namespace App\Http\Requests\Backend\Configuration\AcademicYear;

use App\Http\Requests\Request;

/**
 * Class UpdateAcademicYearRequest
 * @package App\Http\Requests\Backend\Configuration\AcademicYear
 */
class UpdateAcademicYearRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-academicYears');
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
