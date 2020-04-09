<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
          'name' => ['required','max:255'],
          'email' => ['required','email','max:255'],
          'user_level' => ['nullable',Rule::in(User::getUserRolesCodes())],
          'password' => 'nullable|confirmed|max:255',
        ];
    }
}
