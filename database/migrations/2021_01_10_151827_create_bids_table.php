<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedDecimal('amount', 8, 2);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('item_id');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict');

            $table->foreign('item_id')
                ->references('id')->on('items')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bids');
    }
}
