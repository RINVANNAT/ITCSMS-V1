<?php

namespace App\Http\Requests\Backend\Configuration\SchoolFee;

use App\Http\Requests\Request;

/**
 * Class UpdateSchoolFeeRequest
 * @package App\Http\Requests\Backend\Configuration\SchoolFee
 */
class UpdateSchoolFeeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-schoolFees');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to_pay' => 'required',
            'degree_id' => 'required',
            'promotion_id' => 'required',
        ];
    }
}
