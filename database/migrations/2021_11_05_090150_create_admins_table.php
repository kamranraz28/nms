<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->unsigned();
            $table->unsignedInteger('user_type_id')->nullable();
            $table->unsignedInteger('role_id')->nullable();
            $table->unsignedMediumInteger('state_id')->nullable();
            $table->unsignedMediumInteger('division_id')->nullable();
            $table->unsignedMediumInteger('district_id')->nullable();
            $table->unsignedMediumInteger('upazila_id')->nullable();

            $table->unsignedMediumInteger('forest_state_id')->nullable();
            $table->unsignedMediumInteger('forest_division_id')->nullable();
            $table->unsignedMediumInteger('forest_range_id')->nullable();
            $table->unsignedMediumInteger('forest_beat_id')->nullable();
            
            
            $table->string('code')->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('title_bn')->nullable();
            $table->string('title_en')->nullable();
            $table->mediumText('office_bn')->nullable();
            $table->mediumText('office_en')->nullable();
            $table->mediumText('address_bn')->nullable();
            $table->mediumText('address_en')->nullable();
            $table->string('contact',13)->nullable()->default('01*********');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable()->default('$2y$10$Gkvr53MZBrPViW8jKqjq/.3wW7pnzUDZZ61qHUBHh62gCzICJB7We');
            $table->string('thumb',128)->nullable();
            $table->rememberToken();
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
