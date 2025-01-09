<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        return view('role-permission.user.index', ['users' => $users]);
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name', 'name')->all();


        return view('role-permission.user.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'max:20',
                    'regex:/^(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/'
                ],
                'roles' => 'required'
            ],
            [
                'password.regex' => 'The password must be between 8 and 20 characters long and include at least one uppercase letter and one special character.'
            ]

        );
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->roles);


        return redirect('users')->with('success', 'User Updated Successfully with roles');
    }
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return redirect('users')->with('success', 'User Deleted Successfully');
    }
}
