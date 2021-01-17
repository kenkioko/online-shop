<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'name' => ['required','string','max:255'],
          'description' => ['required','string','max:500'],
          'category_id' => ['required','exists:App\Models\Category,id'],
          'price' => ['required','numeric'],          
          'images' => ['required','array'],
          'type' => ['required',Rule::in(array_keys(Item::TYPE))],
          'discount_percent' => ['nullable','numeric'],
          'stock' => ['nullable', 'required_if:type,product','integer'],
        ];
    }
}
