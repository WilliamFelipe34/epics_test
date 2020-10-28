<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLog extends Migration
{
    public function up()
    {
        Schema::create('user_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->text('data_old');
            $table->text('data_new')->nullable();
            $table->timestamps();
        });

        Schema::table('user_logs', function (Blueprint $table) {
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_logs');
    }
}
