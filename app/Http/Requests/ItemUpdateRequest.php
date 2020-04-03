<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $item_shop = $this->route('item')->shop()->firstOrFail();
        return $this->user()->shop()->firstOrFail()->is($item_shop);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'name' => 'required|string|max:255',
          'description' => 'required|string|max:255',
          'category_id' => 'required|exists:categories,id|integer',
          'price' => 'required|numeric',
          'stock' => 'required|integer',
          'images' => 'nullable|array',
          'discount_percent' => 'nullable|numeric',
        ];
    }
}
