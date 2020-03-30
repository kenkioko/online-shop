<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Shop;
use Faker\Generator as Faker;

$factory->define(Shop::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'address' => $faker->unique()->address,
        'user_id' => function () {
            $user = factory(App\User::class)->create();
            $user->assignRole('seller');

            return $user->id;
        },
    ];
});
