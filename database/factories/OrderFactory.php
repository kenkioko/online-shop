<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
      'order_no' => $faker->unique()->uuid,
      'user_id' => function () {
          $user = factory(App\User::class)->create();
          $user->assignRole('buyer');
          return $user->id;
      },
      'total' => $faker->numberBetween($min = 150, $max = 5000),
      'status' => $faker->randomElement($array = Order::getStatusAll(true)),
    ];
});
