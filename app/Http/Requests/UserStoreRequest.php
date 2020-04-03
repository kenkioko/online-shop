<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

class UserStoreRequest extends FormRequest
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
          'name' => ['required','unique:users','max:255'],
          'email' => ['required','unique:users','email','max:255'],
          'user_level' => ['required',Rule::in(User::getUserRoles())],
          'password' => ['required','confirmed','max:255'],
        ];
    }
}
