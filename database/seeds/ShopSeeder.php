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
        // seed db with 3 shops each having 10 items
        $items = factory(App\Models\Shop::class, 3)
            ->create()
            ->each(function ($shop) {
              $shop->items()->saveMany(factory(App\Models\Item::class, 10)->make());
            });
    }
}
