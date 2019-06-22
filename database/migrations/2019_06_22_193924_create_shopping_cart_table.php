<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_cart', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('order_id')->unsigned();
            $table->integer('tour_id')->unsigned();
            $table->integer('price');
            $table->integer('persons');
            $table->date('date');

            $table->unique(array('order_id', 'tour_id'));

            // foreign key constraints are optional (but pretty useful, especially with cascade delete
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_cart');
    }
}
