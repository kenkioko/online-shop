<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Model\Order;

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
          'item_id' => 'required|integer',
          'quantity' => 'required|integer',
          'order_number' => 'required|uuid|exists:orders,order_no',
          'update_type' => ['required', Rule::in(['add', 'edit'])],
        ];
    }
}
