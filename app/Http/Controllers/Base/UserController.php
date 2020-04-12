<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:users.create'])->only(['create', 'store']);
        $this->middleware(['permission:users.update'])->only(['edit', 'update']);
        $this->middleware(['permission:users.delete'])->only(['delete']);
    }

    /**
     * save user to database.
     *
     * @param  array  $validated
     * @param  \App\Models\Category  $category
     * @return boolean
     */
    protected function saveUser($validated, $user)
    {
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $saved = $user->save();

        if ($saved) {
          $user->assignRole($validated['user_level']);
        }
        return $saved;
    }
}
