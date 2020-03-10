<?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Create five App\Category instances,
      // three with sub categories...
      $categories = factory(App\Category::class, 2)->create();
      $categories_sub = factory(App\Category::class, 3)
            ->create()
            ->each(function ($category) {
                $parent = factory(App\Category::class)->create();
                $category->parent_category_id = $parent->id;
                $category->save();
            });
    }
}
