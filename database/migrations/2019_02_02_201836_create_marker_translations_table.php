<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkerTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marker_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('marker_id')->unsigned();
            $table->string('locale')->index();

            $table->string('title',400);
            $table->longText('description');

            $table->unique(['marker_id','locale']);
            $table->foreign('marker_id')->references('id')->on('markers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marker_translations');
    }
}
