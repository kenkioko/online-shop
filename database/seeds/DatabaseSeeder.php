<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
           RolesSeeder::class,
           UsersSeeder::class,
           CategoriesSeeder::class,
           ItemsSeeder::class,
           OrderItemsSeeder::class,
           OrdersSeeder::class,
         ]);
    }
}
