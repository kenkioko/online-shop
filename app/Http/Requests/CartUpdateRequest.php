<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Order;

class CartUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $order_user = Order::findOrFail($this->route('cart'))->user()->firstOrFail();
        return $order_user->is($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'item_id' => ['required','integer'],
          'quantity' => ['required','integer'],
          'order_number' => ['required','uuid','exists:orders,order_no'],
          'update_type' => ['required', Rule::in(['add', 'edit'])],
        ];
    }
}
