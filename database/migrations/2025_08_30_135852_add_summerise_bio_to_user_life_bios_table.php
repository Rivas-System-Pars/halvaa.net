<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSummeriseBioToUserLifeBiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_life_bios', function (Blueprint $table) {
            $table -> string('summerise_bio')->nullable()->after('life_biography');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_life_bios', function (Blueprint $table) {
            $table->dropColumn('summerise_bio');

        });
    }
}
