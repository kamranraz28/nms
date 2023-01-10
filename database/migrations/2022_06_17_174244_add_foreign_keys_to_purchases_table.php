<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreign('nursery_id', 'purchases_fk_nursery_id')
                ->references('id')
                ->on('nurseries')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('budget_id', 'purchases_fk_budget_id')
                ->references('id')
                ->on('budgets')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('division_id', 'purchases_fk_division_id')
                ->references('id')
                ->on('divisions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('district_id', 'purchases_fk_district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('upazila_id', 'purchases_fk_upazila_id')
                ->references('id')
                ->on('upazilas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('state_id', 'purchases_fk_state_id')
                ->references('id')
                ->on('states')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->foreign('stock_type_id', 'purchases_fk_stock_type_id')
                ->references('id')
                ->on('stock_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_state_id', 'purchases_fk_forest_state_id')
                ->references('id')
                ->on('forest_states')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_division_id', 'purchases_fk_forest_division_id')
                ->references('id')
                ->on('forest_divisions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_range_id', 'purchases_fk_forest_range_id')
                ->references('id')
                ->on('forest_ranges')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_beat_id', 'purchases_fk_forest_beat_id')
                ->references('id')
                ->on('forest_beats')
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
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('purchases_fk_nursery_id');
            $table->dropForeign('purchases_fk_budget_id');
            $table->dropForeign('purchases_fk_division_id');
            $table->dropForeign('purchases_fk_district_id');
            $table->dropForeign('purchases_fk_upazila_id');
            $table->dropForeign('purchases_fk_state_id');
            $table->dropForeign('purchases_fk_stock_type_id');

            $table->dropForeign('purchases_fk_forest_state_id');
            $table->dropForeign('purchases_fk_forest_division_id');
            $table->dropForeign('purchases_fk_forest_range_id');
            $table->dropForeign('purchases_fk_forest_beat_id');
        });
    }
}
