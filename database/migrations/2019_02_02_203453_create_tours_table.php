<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('slug')->nullable()->index();

            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->string('duration',100);
            $table->double('price',10,2);

            $table->boolean('active')->default(true);
            $table->integer('views')->default(0);
            $table->json('directions')->nullable();

            $table->integer('tour_category_id')->unsigned();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tour_category_id')->references('id')->on('tour_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tours');
    }
}
