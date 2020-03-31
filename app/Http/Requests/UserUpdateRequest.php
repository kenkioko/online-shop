<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

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
        return [
          'name' => 'required|max:255',
          'email' => 'required|email|max:255',
          'user_level' => 'required|max:255|in:' . implode(',', User::getUserRoles()),
          'password' => 'nullable|confirmed|max:255',
        ];
    }
}
