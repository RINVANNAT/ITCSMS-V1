<?php

namespace App\Http\Requests\Backend\Configuration\AcademicYear;

use App\Http\Requests\Request;

/**
 * Class StoreAcademicYearRequest
 * @package App\Http\Requests\Backend\Configuration\AcademicYear
 */
class StoreAcademicYearRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-academicYears');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'name_latin' => 'required',
            'name_kh' => 'required',
            'date_start_end' => 'required',
        ];
    }
}
