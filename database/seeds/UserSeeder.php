<?php

use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin user
        $admin = User::create([
          'name'=>'admin',
          'email' => 'admin@admin.com',
          'password' => bcrypt('password'),
          'email_verified_at'=> date("Y-m-d H:i:s"),
          'created_at' => date("Y-m-d H:i:s"),
        ]);
        $admin->assignRole('admin');

        // buyer user
        $buyer = User::create([
          'name'=>'johndoe',
          'email' => 'john@doe.com',
          'password' => bcrypt('password'),
          'email_verified_at'=> date("Y-m-d H:i:s"),
          'created_at' => date("Y-m-d H:i:s"),
        ]);
        $buyer->assignRole('buyer');

        // seller user
        $seller = User::create([
          'name'=>'janedoe',
          'email' => 'jane@doe.com',
          'password' => bcrypt('password'),
          'email_verified_at'=> date("Y-m-d H:i:s"),
          'created_at' => date("Y-m-d H:i:s"),
        ]);
        $seller->assignRole('seller');

        // seller shop
        $seller_shop = factory(App\Models\Shop::class)->make();
        $seller_shop->user()->associate($seller);
        $seller_shop->save();

        // seller shop's address and items
        $seller_shop->address()->save(factory(App\Models\Address::class)->make());
        $seller_shop->items()->saveMany(factory(App\Models\Item::class, 10)->make());
    }
}
