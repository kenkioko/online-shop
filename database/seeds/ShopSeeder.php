<?php

use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //seed db with two shops
        $items = factory(App\Models\Shop::class, 2)->create();
    }
}
