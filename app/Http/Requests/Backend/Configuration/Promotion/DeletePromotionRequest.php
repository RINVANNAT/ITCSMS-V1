<?php

namespace App\Http\Requests\Backend\Configuration\Promotion;

use App\Http\Requests\Request;

/**
 * Class DeletePromotionRequest
 * @package App\Http\Requests\Backend\Configuration\Promotion
 */
class DeletePromotionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-promotions');
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
