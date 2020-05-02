<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;
use App\Pivot\OrderItem;
use Faker\Generator as Faker;

$factory->define(OrderItem::class, function (Faker $faker) {
    // $items = Item::select('id')->get();
    // $item_ids = [];
    // foreach ($items as $key => $item) {
    //   array_push($item_ids, $item->id);
    // }

    return [
      'order_id' => function () {
          return factory(App\Models\Order::class)->create()->id;
      },
      'item_id' => function () use ($faker, $item_ids) {
          // if (Item::count() < 5) {
          //   return factory(App\Models\Item::class)->create()->id;
          // }
          return factory(App\Models\Item::class)->create()->id;
      },
      'amount' => $faker->numberBetween($min = 150, $max = 3000),
      'quantity' => $faker->numberBetween($min = 1, $max = 10),
    ];
});
