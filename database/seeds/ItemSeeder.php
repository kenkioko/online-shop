<?php

use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //seed db with ten items
        $items = factory(App\Model\Item::class, 10)->create();
    }
}
