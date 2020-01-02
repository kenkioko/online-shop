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
    ];
});
