<?php

/**
 * Should be called from \App\Model\Shop seeder and create relationship
 *
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */

use App\Models\Item;
use App\Models\Category;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    $categories = Category::select('id')->get();
    $category_ids = [];
    foreach ($categories as $key => $category) {
      array_push($category_ids, $category->id);
    }

    return [
        'name' => $faker->unique()->text($maxNbChars = 10),
        'images_folder' => $faker->uuid,
        'stock' => $faker->numberBetween($min = 0, $max = 100),
        'price' => $faker->numberBetween($min = 100, $max = 3000),
        'description' => $faker->paragraph,
        'category_id' => function () use ($faker, $category_ids) {
            if (Category::count() > 3) {
              return $faker->randomElement($array = $category_ids);
            } else {
              return factory(Category::class)->create()->id;
            }
        },
    ];
});
