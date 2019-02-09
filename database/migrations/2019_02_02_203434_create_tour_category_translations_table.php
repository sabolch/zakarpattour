<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTourCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tour_category_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name',200);

            $table->unique(['tour_category_id','locale']);
            $table->foreign('tour_category_id')->references('id')->on('tour_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_category_translations');
    }
}
