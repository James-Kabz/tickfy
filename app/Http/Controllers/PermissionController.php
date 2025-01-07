<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{
          public static function middleware(): array
          {
                    return [
                              new Middleware('permission:view permission', only: ['index']),
                              new Middleware('permission:delete permission', only: ['destroy']),
                              new Middleware('permission:edit permission', only: ['update', 'edit']),
                              new Middleware('permission:create permission', only: ['create', 'store']),
                    ];
          }
          public function index()
          {
                    $permissions = Permission::get();
                    return view(
                              'role-permission.permission.index',
                              [
                                        'permissions' => $permissions
                              ]
                    );
          }

          public function create()
          {
                    return view('role-permission.permission.create');
          }

          public function store(Request $request)
          {
                    $request->validate([
                              'name' => [
                                        'required',
                                        'string',
                                        'unique:permissions,name'
                              ]
                    ]);

                    Permission::create([
                              'name' => $request->name
                    ]);

                    return redirect('permissions')->with('success', 'Permission Created Successfully');
          }

          public function edit(Permission $permission)
          {
                    return view('role-permission.permission.edit', ['permission' => $permission]);
          }

          public function update(Request $request, Permission $permission)
          {
                    $request->validate([
                              'name' => [
                                        'required',
                                        'string',
                                        'unique:permissions,name,' . $permission->id
                              ]
                    ]);
                    $permission->update([
                              'name' => $request->name
                    ]);

                    return redirect('permissions')->with('success', 'Permission Updated Successfully');
          }

          public function destroy($permissionId)
          {
                    $permission = Permission::findOrFail($permissionId);
                    $permission->delete();
                    return redirect('permissions')->with('success', 'Permission Deleted Successfully');
          }
}
