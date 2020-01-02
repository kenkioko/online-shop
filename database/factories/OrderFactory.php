<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
      'order_no' => $faker->unique()->uuid,
      'user_id' => function () {
          return factory(App\User::class)->create()->id;
      },
      'total' => $faker->numberBetween($min = 150, $max = 5000),
      'status' => $faker->randomElement($array = array (
        'items_in_cart',
        'order_made',
        'processing',
        'enroute',
        'delivered',
        'rejected',
      )),
    ];
});
