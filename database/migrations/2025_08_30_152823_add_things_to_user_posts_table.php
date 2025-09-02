<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThingsToUserPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_posts', function (Blueprint $table) {
            $table->boolean('is_private')->nullable()->default(false)->after('description');
            $table->boolean('is_pin')->nullable()->default(false)->after('is_private');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_posts', function (Blueprint $table) {
            //
        });
    }
}
