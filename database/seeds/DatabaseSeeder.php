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
           RoleSeeder::class,
           UserSeeder::class,   //disabled on production
           ItemSeeder::class,   //disabled on production
         ]);
    }
}
