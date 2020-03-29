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
        define('PERMISSIONS', [
          'view',     // permissions[0]
          'create',   // permissions[1]
          'update',   // permissions[2]
          'delete',   // permissions[3]
        ], true);
        define('OBJECTS', [
          'users',      // objects[0]
          'roles',      // objects[1]
          'items',      // objects[2]
          'categories', // objects[3]
          'orders',     // objects[4]
          'cart',       // objects[5]
          'shop',       // objects[6]
        ], true);

        // create object permissions
        foreach (objects as $index => $object) {
          $this->create_permissions(permissions, $object);
        }

        // non-object permissions
        $view_dash = Permission::create(['name' => 'dashboard.view']);
        $view_web = Permission::create(['name' => 'website.view']);

        // admin role
        $admin_role = Role::create(['name' => 'admin']);
        $admin_role->givePermissionTo($view_dash);
        $this->set_user_permissions(permissions, [      // All permissions
          objects[0],  // users
          objects[1],  // roles
          objects[3],  // categories
          objects[6],  // shop
        ], $admin_role);
        $this->set_user_permissions([permissions[0]], [   // view permissions
          objects[2],  // items
        ], $admin_role);

        // buyer role
        $buyer_role = Role::create(['name' => 'buyer']);
        $buyer_role->givePermissionTo($view_web);
        $this->set_user_permissions(permissions, [      // All permissions
          objects[4],  // orders
          objects[5],  // cart
        ], $buyer_role);
        $this->set_user_permissions([permissions[0]], [   // view permissions
          objects[2],  // items
          objects[3],  // categories
          objects[6],  // shop
        ], $buyer_role);

        // seller role
        $seller_role = Role::create(['name' => 'seller']);
        $seller_role->givePermissionTo($view_dash);
        $this->set_user_permissions(permissions, [      // All permissions
          objects[2],  // items
          objects[4],  // orders
          objects[6],  // shop
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
        return '' .$object. '.' .$permission;  // 'object.permission'
    }
}
