<?php

namespace App\Http\Requests\Backend\Accounting\Income;

use App\Http\Requests\Request;

/**
 * Class UpdateIncomeRequest
 * @package App\Http\Requests\Backend\Accounting\Income
 */
class UpdateIncomeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-incomes');
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
