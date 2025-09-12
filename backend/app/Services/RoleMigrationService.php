<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\User;

class RoleMigrationService
{
    public static function createRoles()
    {
        $admin = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web',
            'nameRu' => 'Администратор'
        ]);

        $active = Role::create([
            'name' => 'Active',
            'guard_name' => 'web',
            'nameRu' => 'Активный пользователь'
        ]);


        //Роль админу.
        $userId =  User::where('email', 'admin@m.ru')->first()->id;
        $user = User::find($userId);
        $user->assignRole('Admin');
    }

    public static function createPermissions()
    {
        /*
        //Permissions
        $admin = Permission::create([
           'name' => 'admin',
           'guard_name' => 'web'
        ]);

         //Permissions
        $admin = Permission::create([
           'name' => 'admin',
           'guard_name' => 'web'
        ]);
        */
    }

    public static function givePermissionsToRoles()
    {

        $adminRole =  Role::firstWhere('name', 'Admin');
        $activeRole =  Role::firstWhere('name', 'Active');
      /*  $activeRole->givePermissionTo(['view user','edit user']);
        $AdminRole->givePermissionTo([

            'view user',
            'edit user',
            'admin'
            ]);
     */
    }

    public static function deleteALlRoles()
    {
        Role::truncate();
        Permission::truncate();
        RoleHasPermission::truncate();
    }
}
