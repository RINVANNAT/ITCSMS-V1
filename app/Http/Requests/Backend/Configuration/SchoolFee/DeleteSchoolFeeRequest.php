<?php

namespace App\Http\Requests\Backend\Configuration\SchoolFee;

use App\Http\Requests\Request;

/**
 * Class DeleteSchoolFeeRequest
 * @package App\Http\Requests\Backend\Configuration\SchoolFee
 */
class DeleteSchoolFeeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-schoolFees');
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
