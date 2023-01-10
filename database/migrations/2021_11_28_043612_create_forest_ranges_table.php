<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForestRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forest_ranges', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('forest_state_id')->nullable()->index('forest_ranges_fk_forest_state_id');
            $table->unsignedMediumInteger('forest_division_id')->nullable()->index('forest_ranges_fk_forest_division_id');
            
            $table->unsignedMediumInteger('division_id')->nullable()->index('forest_ranges_fk_division_id');
            $table->unsignedMediumInteger('district_id')->nullable()->index('forest_ranges_fk_district_id');
            $table->unsignedMediumInteger('upazila_id')->nullable()->index('forest_ranges_fk_upazila_id');
            
            $table->string('name')->nullable();
            $table->string('title_bn')->nullable();
            $table->string('title_en')->nullable();
            $table->char('forest_division_bbs_code', 5)->nullable();
            $table->char('bbs_code', 5)->nullable();

            //$table->smallInteger('sort')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('forest_state_id', 'forest_ranges_fk_forest_state_id')
                ->references('id')
                ->on('forest_states')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_division_id', 'forest_ranges_fk_forest_division_id')
                ->references('id')
                ->on('forest_divisions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('division_id', 'forest_ranges_fk_division_id')
                ->references('id')
                ->on('divisions')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('district_id', 'forest_ranges_fk_district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('upazila_id', 'forest_ranges_fk_upazila_id')
                ->references('id')
                ->on('upazilas')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forest_ranges');
    }
}
