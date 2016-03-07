<?php

namespace App\Http\Requests\Backend\Accounting\Customer;

use App\Http\Requests\Request;

/**
 * Class DeleteCustomerRequest
 * @package App\Http\Requests\Backend\Accounting\Customer
 */
class DeleteCustomerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-customers');
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
