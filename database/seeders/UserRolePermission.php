<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);
        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);

        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin


        $allPermissionNames = Permission::pluck('name')->toArray();
        $superAdminRole->givePermissionTo($allPermissionNames);


        // Let's Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
            'email' => 'kabogp@gmail.com',
        ], [
            'name' => 'Tickfy admin',
            'email' => 'kabogp@gmail.com',
            'password' => Hash::make('@kabz123'),
        ]);

        $superAdminUser->assignRole($superAdminRole);

    }
}
