<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForestDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forest_divisions', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('forest_state_id')->nullable()->index('forest_divisions_fk_forest_state_id');
            $table->string('name')->nullable();
            $table->string('title_bn')->nullable();
            $table->string('title_en')->nullable();
            $table->char('bbs_code', 5)->nullable();

            //$table->smallInteger('sort')->default(1);
            $table->boolean('price_type')->nullable()->default(true)->comment('Regular = 1, Irregular = 0');
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('forest_state_id', 'forest_divisions_fk_forest_state_id')
                ->references('id')
                ->on('forest_states')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forest_divisions');
    }
}
