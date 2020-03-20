<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OrderItem;
use Faker\Generator as Faker;

$factory->define(OrderItem::class, function (Faker $faker) {
    return [
      'order_id' => function () {
          return factory(App\Order::class)->create()->id;
      },
      'item_id' => function () {
          return factory(App\Item::class)->create()->id;
      },
      'amount' => $faker->numberBetween($min = 150, $max = 3000),
      'quantity' => $faker->numberBetween($min = 1, $max = 10),
    ];
});
