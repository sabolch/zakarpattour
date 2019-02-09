<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkerToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marker_tours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('marker_id')->unsigned();
            $table->integer('tour_id')->unsigned();
            $table->unique(array('marker_id', 'tour_id'));

            // foreign key constraints are optional (but pretty useful, especially with cascade delete
            $table->foreign('marker_id')->references('id')->on('markers')->onDelete('cascade');
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
        Schema::dropIfExists('marker_tours');
    }
}
