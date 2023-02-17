<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCitiesTable extends Migration
{
    public function up()
    {
        Schema::create('user_cities', function (Blueprint $table) {
            $table->id();
            $table->string('city_name');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('user_cities');
    }
}
