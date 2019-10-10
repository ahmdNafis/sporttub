<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 400)->nullable();
            $table->string('product_name', 250);
            $table->text('description')->nullable();
            $table->longtext('product_link')->nullable();
            $table->string('color', 200)->nullable();
            $table->string('manufacturer', 200)->nullable();
            $table->decimal('weight', 7, 2)->nullable();
            $table->string('weight_units', 40)->nullable();
            $table->decimal('retail_price', 9, 3)->nullable();
            $table->decimal('wholesale_price', 9, 3)->nullable();
            $table->decimal('discount', 5, 2)->nullable();
            $table->boolean('product_status')->default(false);
            $table->unsignedInteger('category_id')->index();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
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
        Schema::dropIfExists('products');
    }
}
