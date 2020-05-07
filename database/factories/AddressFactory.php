<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Address;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'country' => $faker->country,
        'state' => $faker->state,
        'city' => $faker->city,
        'street' => $faker->streetAddress,
        'postcode' => $faker->postcode,
        'full_address' => $faker->unique()->address,
        'latitude' => $faker->unique()->latitude,
        'longitude' => $faker->unique()->longitude,
    ];
});
