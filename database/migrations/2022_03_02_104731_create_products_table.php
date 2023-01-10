<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedMediumInteger('unit_id')->nullable();
            $table->unsignedMediumInteger('size_id')->nullable();
            $table->unsignedMediumInteger('color_id')->nullable();
            $table->unsignedMediumInteger('age_id')->nullable();
            $table->string('code')->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('title_bn')->nullable();
            $table->string('title_en')->nullable();
            $table->string('scientific_bn')->nullable();
            $table->string('scientific_en')->nullable();
            $table->mediumText('details_bn')->nullable();
            $table->mediumText('details_en')->nullable();
            $table->double('price', 10, 2)->nullable()->default(0);
            $table->double('price_bag', 10, 2)->nullable()->default(0);
            $table->double('price_10', 10, 2)->nullable()->default(0);
            $table->double('price_12', 10, 2)->nullable()->default(0);
            $table->boolean('percent')->nullable()->default(false);
            $table->float('discount')->nullable()->default(0);
            $table->boolean('saleable')->nullable()->default(true);
            $table->string('thumb',128)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
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
