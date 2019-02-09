<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markers', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->float('lat',12,8);
            $table->float('lng',12,8);
            $table->integer('marker_category_id')->unsigned();
            $table->string('slug')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('marker_category_id')->references('id')->on('marker_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markers');
    }
}
