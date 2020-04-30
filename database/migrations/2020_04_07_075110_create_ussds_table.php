<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\USSD;

class CreateUSSDsTable extends Migration
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
            $table->string('text')->default('');
            $table->string('level_data')->default('');
            $table->unsignedInteger('ussd_level')->default(0);            
            $table->unsignedBigInteger('ussd_id')->nullable();
            $table->enum('provider', USSD::getProviders());
            $table->timestamps();
        });

        Schema::table('ussds', function (Blueprint $table) {
            $table->foreign('ussd_id')
                  ->references('id')->on('ussds')
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
        Schema::dropIfExists('ussds');
    }
}
