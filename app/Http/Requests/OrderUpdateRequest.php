<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderUpdateRequest extends FormRequest
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
          'item_id' => 'required|integer',
          'order_number' => 'required|uuid',
          'status' => 'nullable|string|max:255',
          'update_type' => ['required', Rule::in(['add', 'remove'])],
        ];
    }
}
