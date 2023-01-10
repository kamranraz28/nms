<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedMediumInteger('stock_type_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('sale_id')->nullable();
            $table->unsignedInteger('nursery_id')->nullable();
            $table->unsignedMediumInteger('budget_id')->nullable();
            $table->unsignedMediumInteger('state_id')->nullable();
            $table->unsignedMediumInteger('division_id')->nullable();
            $table->unsignedMediumInteger('district_id')->nullable();
            $table->unsignedMediumInteger('upazila_id')->nullable();

            $table->unsignedMediumInteger('forest_state_id')->nullable();
            $table->unsignedMediumInteger('forest_division_id')->nullable();
            $table->unsignedMediumInteger('forest_range_id')->nullable();
            $table->unsignedMediumInteger('forest_beat_id')->nullable();


            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedMediumInteger('unit_id')->nullable();
            $table->unsignedMediumInteger('size_id')->nullable();
            $table->unsignedMediumInteger('color_id')->nullable();
            $table->unsignedMediumInteger('age_id')->nullable();
            $table->unsignedMediumInteger('price_type_id')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->year('year')->nullable();
            $table->integer('quantity')->nullable()->default(0);
            $table->double('price', 10, 2)->nullable()->default(0);
            $table->double('total', 10, 2)->nullable()->default(0);
            $table->boolean('free')->nullable()->default(false);
            $table->tinyInteger('walking')->default(0);
            $table->tinyInteger('app_status')->default(1)->comment('1=BO,2=RO,3=ACF,4=DFO');
            $table->boolean('approved')->nullable()->default(false);
            $table->boolean('web')->nullable()->default(false);
            $table->tinyInteger('status')->default(1);
            $table->date('vch_date')->nullable();
            $table->integer('approved_by')->nullable();
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
        Schema::dropIfExists('sale_details');
    }
}
