<?php

namespace App\Http\Requests\Backend\Configuration\SchoolFee;

use App\Http\Requests\Request;

/**
 * Class CreateSchoolFeeRequest
 * @package App\Http\Requests\Backend\Configuration\SchoolFee
 */
class CreateSchoolFeeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-schoolFees');
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
