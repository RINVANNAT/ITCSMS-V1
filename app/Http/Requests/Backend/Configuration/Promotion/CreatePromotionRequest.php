<?php

namespace App\Http\Requests\Backend\Configuration\Promotion;

use App\Http\Requests\Request;

/**
 * Class CreatePromotionRequest
 * @package App\Http\Requests\Backend\Configuration\Promotion
 */
class CreatePromotionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-promotions');
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
