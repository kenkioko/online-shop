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
        $view_dash = Permission::create(['name' => 'dashboard.view']);

        // admin role
        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->givePermissionTo($view_dash);
        $this->set_user_permissions(permissions, objects, $admin_role);

        // buyer role
        $buyer_role = Role::create(['name' => 'buyer']);
        $this->set_user_permissions(permissions, [      // All permissions
          objects[4],  // orders
          objects[5],  // cart
        ], $buyer_role);
        $this->set_user_permissions([permissions[0]], [   // view permissions
          objects[2],  // items
          objects[3],  // categories
        ], $buyer_role);

        // seller role
        $seller_role = Role::create(['name' => 'seller']);
        $seller_role->givePermissionTo($view_dash);
        $this->set_user_permissions(permissions, [      // All permissions
          objects[2],  // items
          objects[4],  // orders
        ], $seller_role);
        $this->set_user_permissions([permissions[0]], [   // view permissions
          objects[3],  // categories
        ], $seller_role);
    }

    protected function create_permissions($permissions, $object)
    {
        foreach ($permissions as $index => $permission) {
          Permission::create(['name' => $this->permission_name($permission, $object)]);
        }
    }

    protected function set_user_permissions($permissions, $objects, $role)
    {
        $get_permissions = function ($object) use ($permissions, $role)
        {
          foreach ($permissions as $index => $permission) {
            $permission_name = $this->permission_name($permission, $object);
            $role->givePermissionTo($permission_name);
          }
        };

        foreach ($objects as $index => $object) {
          $permissions = $get_permissions($object);
        }
    }

    private function permission_name($permission, $object)
    {
        return '' .$object. '.' .$permission;
    }
}
