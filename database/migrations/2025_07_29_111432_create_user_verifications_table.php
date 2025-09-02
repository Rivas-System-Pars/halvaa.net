<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('national_card');
            $table->string('birth_certificate');
            $table->string('death_cerification');
            $table->enum('status',['pending' , 'approved' , 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
           $table->timestamps();


           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_verifications');
    }
}
