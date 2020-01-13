<?php

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
        $admin_role = Role::create(['name' => 'admin']);
        $buyer_role = Role::create(['name' => 'buyer']);

        $all_permissions = Permission::create(['name' => 'all permissions']);
        $admin_role->givePermissionTo($all_permissions);
    }
}
