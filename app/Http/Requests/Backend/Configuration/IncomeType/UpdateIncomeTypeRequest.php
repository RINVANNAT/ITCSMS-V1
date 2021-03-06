<?php

namespace App\Http\Requests\Backend\Configuration\IncomeType;

use App\Http\Requests\Request;

/**
 * Class UpdateIncomeTypeRequest
 * @package App\Http\Requests\Backend\Configuration\IncomeType
 */
class UpdateIncomeTypeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-incomeTypes');
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
