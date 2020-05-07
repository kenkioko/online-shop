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
        factory(App\Models\Shop::class, 3)
            ->make()
            ->each(function ($shop) {
                // shop user
                $seller = factory(App\User::class)->create();
                $seller->assignRole('seller');
                $shop->user()->associate($seller);
                $shop->save();

                // shop's address and items
                $shop->address()->save(factory(App\Models\Address::class)->make());
                $shop->items()->saveMany(factory(App\Models\Item::class, 10)->make());
            });
    }
}
