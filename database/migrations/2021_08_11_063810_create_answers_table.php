<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('aid');
            $table->integer('qid')->unsigned();
            $table->integer('cid')->unsigned();

            $table->foreign('qid')->references('qid')->on('questions');
            $table->foreign('cid')->references('cid')->on('choices');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('answers');
    }
}
