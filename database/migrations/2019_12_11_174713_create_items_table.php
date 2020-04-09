<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->uuid('images_folder')->nullable();
            $table->unsignedInteger('stock');
            $table->unsignedDecimal('price', 8, 2);
            $table->unsignedDecimal('discount_amount', 8, 2)->nullable();
            $table->unsignedInteger('discount_percent')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('shop_id');
            $table->timestamps();

            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('restrict');

            $table->foreign('shop_id')
                  ->references('id')->on('shops')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
