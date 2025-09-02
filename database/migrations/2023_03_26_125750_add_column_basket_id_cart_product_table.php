<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBasketIdCartProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_product',function (Blueprint $table){
            $table->unsignedBigInteger('basket_id')->nullable();
            $table->foreign('basket_id')->references('id')->on('baskets')
            ->nullOnDelete()
            ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_product',function (Blueprint $table){
            $table->dropColumn('basket_id');
        });
    }
}
