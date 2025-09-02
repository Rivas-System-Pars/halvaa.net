<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBirthPlaceDeathPlaceIsVerifiedToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->foreignId('birth_city_id')->nullable()->after('birth')->constrained('cities')->nullOnDelete();
            $table->foreignId('death_city_id')->nullable()->after('death')->constrained('cities')->nullOnDelete();
            $table->boolean('is_verified')->default(false)->after('is_private');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['birth_city_id']);
            $table->dropForeign(['death_city_id']);
            $table->dropColumn(['birth_city_id', 'death_city_id', 'is_verified']);
        });
    }
}
