<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->text('heading');
            $table->text('sub-heading')->nullable();
            $table->longText('content');
            $table->boolean('published_status')->default(false);
            $table->text('newslink')->nullable();
            $table->text('videolink')->nullable();
            $table->dateTime('published_date');
            $table->dateTime('updated_date')->nullable();
            $table->unsignedInteger('category_id')->index();
            //$table->timestamps();
        });

        Schema::table('news', function(Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
