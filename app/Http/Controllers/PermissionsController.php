<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        return Permission::all();
    }

    public function show(Permission $permission)
    {
        return $permission;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string']
        ]);
        $permission = Permission::create([
            'name' => $request->get('name'),
            'guard_name' => 'web'
        ]);

        return $permission;
    }

    public function update(Permission $permission)
    {
        $permission->update(['name' => request('name')]);
        return $permission;
    }

    public function destroy(Permission $permission)
    {
        return $permission->delete();
    }
}
