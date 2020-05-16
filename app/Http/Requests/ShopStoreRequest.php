<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shop_name' => ['required','string','max:100'],
            'shop_country' => ['required','string','max:100'],
            'shop_state' => ['required','string','max:100'],
            'shop_city' => ['required','string','max:100'],
            'shop_street' => ['required','string','max:200'],
            'shop_postcode' => ['required','string','max:50'],
            'shop_full_address' => ['required','string','max:500'],
        ];
    }
}
