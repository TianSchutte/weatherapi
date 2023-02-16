<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherStatsTable extends Migration
{
    public function up()
    {
        Schema::create('weather_stats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('user_cities');

            $table->json('weather_data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('weather_stats');
    }
}
