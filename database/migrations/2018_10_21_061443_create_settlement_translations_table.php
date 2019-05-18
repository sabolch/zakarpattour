<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlementTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('settlement_id')->unsigned();
                $table->string('locale')->index();

                $table->string('title',400);
                $table->longText('description');

                $table->unique(['settlement_id','locale']);
                $table->foreign('settlement_id')->references('id')->on('settlements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settlement_translations');
    }
}
