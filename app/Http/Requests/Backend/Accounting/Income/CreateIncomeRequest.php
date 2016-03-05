<?php

namespace App\Http\Requests\Backend\Accounting\Income;

use App\Http\Requests\Request;

/**
 * Class CreateIncomeRequest
 * @package App\Http\Requests\Backend\Accounting\Income
 */
class CreateIncomeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-incomes');
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
