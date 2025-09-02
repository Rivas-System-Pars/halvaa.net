<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMemorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_memorials', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('target_user_id');
            $table->foreign('target_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('writer_user_id');
            $table->foreign('writer_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('text');
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
        Schema::dropIfExists('user_memorials');
    }
}
