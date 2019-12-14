<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'images_folder' => $faker->image,
        'stock' => $faker->numberBetween($min = 0, $max = 100),
        'price' => $faker->numberBetween($min = 100, $max = 9000),
        'description' => $faker->paragraph,
        'category_id' => function () {
            return factory(App\Category::class)->create()->id;
        },
    ];
});
