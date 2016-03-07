<?php

namespace App\Http\Requests\Backend\Accounting\Customer;

use App\Http\Requests\Request;

/**
 * Class StoreCustomerRequest
 * @package App\Http\Requests\Backend\Accounting\Customer
 */
class StoreCustomerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-customers');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'address' =>'max:255',
            'phone' => 'max:100',
            'email' => 'max:100',
            'company' => 'max:100',
            'identity_number' => 'max:100'
        ];
    }
}
