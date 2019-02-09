<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkerCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marker_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('marker_category_id')->unsigned();
            $table->string('locale')->index();

            $table->string('name',200);

            $table->unique(['marker_category_id','locale']);
            $table->foreign('marker_category_id')->references('id')->on('marker_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marker_category_translations');
    }
}
