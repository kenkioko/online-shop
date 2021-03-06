<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use App\Models\Shop;
use App\Models\Item;
use App\Pivot\OrderItem;
use App\Http\Controllers\Dash\OrderController;


class OrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $shop = $this->user()->shop()->firstOrFail();
        $order = $this->route('order');

        $total_items = $order::whereHas('items', function (Builder $query) use ($shop) {
          $query->where('shop_id', $shop->id);
        })->count();

        if ($total_items > 0) {
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
          'status' => ['required', 'json'],
        ];
    }
}
