<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Model\USSD;

class CreateUSSDTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ussds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sessionId');
            $table->string('phoneNumber');
            $table->string('networkCode');
            $table->string('serviceCode');
            $table->string('text')->nullable();
            $table->enum('provider', USSD::getProviders());
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
        Schema::dropIfExists('ussds');
    }
}
