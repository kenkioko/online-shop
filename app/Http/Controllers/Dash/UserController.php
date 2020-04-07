<?php

namespace App\Http\Controllers\Dash;

use App\User;
use App\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Controllers\Base\ShopController;
use App\Http\Controllers\Base\UserController as Controller;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['permission:users.view']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('dash.users')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dash.user_form')->with([
          'user' => null,
          'shop' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();
        $shop_data = ($validated['user_level'] == 'seller') ?
          ShopController::validateData($request) :
          null;

        if ($this->save(new User, $validated, $shop_data)) {
          return redirect()->route('admin.users.index')->with([
            'users' => User::all(),
            'success' => 'User added successfully',
          ]);
        }

        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $shop = null;
        if ($user->hasRole('seller')) {
          $shop = $user->shop()->firstOrFail();
        }

        return view('dash.user_view')->with([
          'user' => $user,
          'shop' => $shop,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $shop = null;
        if ($user->hasRole('seller')) {
          $shop = $user->shop()->firstOrFail();
        }

        return view('dash.user_form')->with([
          'user' => $user,
          'shop' => $shop,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $validated = $request->validated();
        $shop_data = ($validated['user_level'] == 'seller') ?
          ShopController::validateData($request) :
          null;

        if ($this->save($user, $validated, $shop_data)) {
          return redirect()->route('admin.users.index')->with([
            'users' => User::all(),
            'success' => 'User edited successfully',
          ]);
        }

        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user === Auth::user()) {
          return back()->with([
            'error' => 'Cannot delete logged in user'
          ]);
        }

        if ($this->delete($user)) {
          return redirect()->route('admin.users.index')->with([
            'users' => User::all(),
            'success' => 'User deleted successfully',
          ]);
        }

        return redirect()->route('admin.users.index')->with([
          'users' => User::all(),
          'error' => 'There was an error deleting user ' .$user->name,
        ]);
    }

    /**
     * Saves the user deatails and also shop details if seller.
     *
     * @param  \App\User  $user
     * @param  array $validated
     * @param  array  $shop_data
     * @return true/false
     */
    private function save($user, $validated, $shop_data)
    {
        return DB::transaction(function () use ($user, $validated, $shop_data) {
          $user_success = $this->saveUser($validated, $user);
          $shop_success = ($shop_data) ? ShopController::saveShopDetails($shop_data, $user) : true;

          return ($user_success and $shop_success);
        });
    }

    private function delete($user)
    {
        return DB::transaction(function () use ($user) {
          if ($user->shop()->count() > 0) {
            $user->shop()->delete();
          }

          $user->delete();

          return true;
        });
    }
}
