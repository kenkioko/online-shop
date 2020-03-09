<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Order;

class CartUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $order_user_id = Order::findOrFail($this->route('cart'))->user_id;
        if ($order_user_id === $this->user()->id) {
          return true;
        }

        return fale;
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
          'order_number' => 'exists:orders,order_no',
          'update_type' => ['required', Rule::in(['add', 'remove'])],
        ];
    }
}
