<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('close_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');        // صاحب لیستِ کلوز (کاربر احراز هویت شده)
            $table->unsignedBigInteger('close_user_id');   // کاربری که داخل لیست کلوز قرار می‌گیرد
            $table->timestamps();

            $table->unique(['owner_id', 'close_user_id']);

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('close_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('close_users');
    }
}
