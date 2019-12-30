<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $user_levels=array('admin', 'buyer');

        return [
          'name' => 'required|max:255',
          'email' => 'required|email|max:255',
          'user_level' => 'required|max:255|in:' . implode(',', $user_levels),
          'password' => 'nullable|confirmed|max:255',
        ];
    }
}
