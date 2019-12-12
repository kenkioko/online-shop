<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'images_folder' => $faker->image,
        'price' => $faker->numberBetween($min = 100, $max = 9000),
        'category_id' => function () {
            return factory(App\Category::class)->create()->id;
        },
    ];
});
