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
           CategoriesTableSeeder::class,
           ItemsTableSeeder::class,
           OrderItemSeeder::class,
           OrderSeeder::class,
           RoleSeeder::class,
           UserSeeder::class,
         ]);
    }
}
