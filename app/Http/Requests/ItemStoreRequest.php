<?php

namespace App\Http\Requests;

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
          'name' => 'required|string|unique:items|max:255',
          'description' => 'required|string|max:500',
          'category_id' => 'required|exists:categories,id|integer',
          'price' => 'required|numeric',
          'stock' => 'required|integer',
          'images' => 'required|array',
          'discount_percent' => 'nullable|numeric',
        ];
    }
}
