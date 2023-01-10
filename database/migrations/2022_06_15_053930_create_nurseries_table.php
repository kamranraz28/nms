<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNurseriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nurseries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_id')->nullable();
            $table->unsignedMediumInteger('state_id')->nullable();
            $table->unsignedMediumInteger('division_id')->nullable();
            $table->unsignedMediumInteger('district_id')->nullable();
            $table->unsignedMediumInteger('upazila_id')->nullable();
            
            $table->string('code')->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('title_bn')->nullable();
            $table->string('title_en')->nullable();
            $table->mediumText('office_bn')->nullable();
            $table->mediumText('office_en')->nullable();
            $table->mediumText('address_bn')->nullable();
            $table->mediumText('address_en')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable()->default('$2y$10$Gkvr53MZBrPViW8jKqjq/.3wW7pnzUDZZ61qHUBHh62gCzICJB7We');
            $table->mediumText('details_bn')->nullable();
            $table->mediumText('details_en')->nullable();
            $table->string('latitude',32)->nullable();
            $table->string('longitude',32)->nullable();
            $table->string('contact',13)->nullable()->default('01*********');
            $table->string('thumb',128)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('nurseries');
    }
}
