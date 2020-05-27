<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
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
            'address_country' => ['nullable','string','max:100'],
            'address_state' => ['nullable','string','max:100'],
            'address_city' => ['nullable','string','max:100'],
            'address_street' => ['nullable','string','max:200'],
            'address_postcode' => ['nullable','string','max:50'],
            'address_full' => ['required','string','max:500'],
        ];
    }
}
