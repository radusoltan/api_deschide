<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index(){
        return Role::with('permissions')->get();
    }

    public function show(Role $role)
    {

        return $role;

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $permissions = Permission::findMany($request->get('roles'))->pluck('id','id');
        $role = Role::create([
            'name' => $request->get('name'),
            "guard_name" => 'web'
        ]);

        $role->syncPermissions($permissions);

        return $role;

    }

    public function update(Role $role)
    {

        request()->validate([
            'name' => ['required','string'],
            'selectedPermissions' => ['array']
        ]);

        $role->update([
            'name' => request('name')
        ]);

        $permissions = Permission::findMany(request('selectedPermissions'))->pluck('id','id');
        // dump($permissions);
        $role->syncPermissions($permissions);

        return $role->with(['permissions'])->get();
    }

    public function destroy(Role $role)
    {
        return $role->delete();
    }
}
