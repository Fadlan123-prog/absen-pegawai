<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        $dashboard = Permission::create(['name' => 'dashboard']);
        $userManagement = Permission::create(['name' => 'userManagement']);
        $attendanceManagement = Permission::create(['name' => 'attendanceManagement']);
        $dailyreportsManagement = Permission::create(['name' => 'dailyReportsManagement']);

        //admin
        $admin->givePermissionTo($dashboard);
        $admin->givePermissionTo($userManagement);
        $admin->givePermissionTo($dailyreportsManagement);


        //user
        $user->givePermissionTo($dashboard);
        $user->givePermissionTo($attendanceManagement);
        $user->givePermissionTo($dailyreportsManagement);
    }
}
