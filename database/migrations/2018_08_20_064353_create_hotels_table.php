<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hotel_code');
            $table->string('name');
            $table->string('city');
            $table->string('city_id');
            $table->string('country_id');
            $table->string('country_code');
            $table->string('rating');
            $table->string('hotel_address');
            $table->string('hotel_postal_code');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('desc');
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
        Schema::dropIfExists('hotels');
    }
}
