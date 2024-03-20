<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::with(['roles','permissions'])->paginate(10);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('selectedRoles')){
           $user->assignRole($request->get('selectedRoles'));
        }

        return $user->load('roles.permissions');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     *
     * @return \App\Models\User
     */
    public function show(User $user)
    {
        return $user->load('roles.permissions');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     *
     * @return \App\Models\User
     */
    public function update(Request $request, User $user)
    {
        $user->update([
            'name' => $request->get('name'),
            'email' => $request->get('email')
        ]);

        if ($request->has('selectedRoles')){
            $user->assignRole($request->get('selectedRoles'));
        }

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        return $user->delete();
    }
}
