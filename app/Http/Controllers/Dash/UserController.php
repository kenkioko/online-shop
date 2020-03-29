<?php

namespace App\Http\Controllers\Dash;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Controllers\UserController as Controller;

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
        return view('dash.user_form')->with('user', null);
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
        $user = new User;

        if (! $this->save($validated, $user)) {
          return back()->withInput();
        }

        return redirect()->route('admin.users.index')->with([
          'users' => User::all(),
          'success' => 'User added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('dash.user_form')->with('user', $user);
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
        if (! $this->save($request->validated(), $user)) {
          return back()->withInput();
        }

        return redirect()->route('admin.users.index')->with([
          'users' => User::all(),
          'success' => 'User edited successfully',
        ]);
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

        $user->delete();
        return redirect()->route('admin.users.index')->with([
          'users' => User::all(),
          'success' => 'User deleted successfully'
        ]);
    }

    /**
     * save user to database.
     *
     * @param  array  $validated
     * @param  \App\Category  $category
     * @return boolean
     */
    protected function save($validated, $user)
    {
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);

        $save = $user->save();
        $user->assignRole($validated['user_level']);

        return $save;
    }
}
