<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // object permissions
        define('PERMISSIONS', ['view', 'create', 'update', 'delete'], true);
        define('OBJECTS', ['users', 'roles', 'items', 'categories', 'orders', 'cart'], true);
        foreach (objects as $index => $object) {
          $this->create_permissions(permissions, $object);
        }

        // non-object permissions
        $view_dash = Permission::create(['name' => 'view dashboard']);

        // admin role
        $admin_role = Role::create(['name' => 'admin',]);
        $admin_role->givePermissionTo($view_dash);
        $this->set_user_permissions(permissions, objects, $admin_role);

        // buyer role
        $buyer_role = Role::create(['name' => 'buyer']);
        $this->set_user_permissions(permissions, [
          objects[4],  // orders
          objects[5],  // cart
        ], $buyer_role);

        // seller role
        $seller_role = Role::create(['name' => 'seller']);
        $buyer_role->givePermissionTo($view_dash);
        $this->set_user_permissions(permissions, [
          objects[2],  // items
          objects[4],  // orders
        ], $buyer_role);
    }

    protected function create_permissions($permissions, $object)
    {
        foreach ($permissions as $index => $permission) {
          Permission::create(['name' => '' .$object . ' '   .$permission]);
        }
    }

    protected function set_user_permissions($permissions, $objects, $role)
    {
        $get_permissions = function ($object) use ($permissions, $role)
        {
          foreach ($permissions as $index => $permission) {
            $permission_name = '' .$object . ' '   .$permission;
            $role->givePermissionTo($permission_name);
          }
        };

        foreach ($objects as $index => $object) {
          $permissions = $get_permissions($object);
        }
    }
}
