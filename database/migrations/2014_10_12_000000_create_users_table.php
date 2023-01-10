<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable()->default('1001');
            $table->unsignedMediumInteger('state_id')->nullable();
            $table->unsignedMediumInteger('division_id')->nullable();
            $table->unsignedMediumInteger('district_id')->nullable();
            $table->unsignedMediumInteger('upazila_id')->nullable();
            $table->string('name')->nullable()->default('Mr.Default User');
            $table->string('title_bn')->nullable()->default('মিস্টার ডিফল্ট ইউসার');
            $table->string('title_en')->nullable()->default('Mr.Default User');
            $table->string('contact')->nullable()->default('017********');
            $table->string('username')->nullable()->default('017********');
            $table->string('email')->nullable()->default('default_user@gmail.com');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable()->default(Hash::make('password'));
            $table->string('thumb',128)->nullable();
            $table->rememberToken();
            $table->tinyInteger('default')->default(0);
            $table->tinyInteger('walking')->default(0);
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
        Schema::dropIfExists('users');
    }
}
