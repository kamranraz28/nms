<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->foreign('purchase_id', 'purchase_details_fk_purchase_id')
                ->references('id')
                ->on('purchases')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('product_id', 'purchase_details_fk_product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('nursery_id', 'purchase_details_fk_nursery_id')
                ->references('id')
                ->on('nurseries')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('budget_id', 'purchase_details_fk_budget_id')
                ->references('id')
                ->on('budgets')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('division_id', 'purchase_details_fk_division_id')
                ->references('id')
                ->on('divisions')
                ->onUpdate('SET NULL')
                ->onDelete('cascade');

            $table->foreign('district_id', 'purchase_details_fk_district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('upazila_id', 'purchase_details_fk_upazila_id')
                ->references('id')
                ->on('upazilas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->foreign('state_id', 'purchase_details_fk_state_id')
                ->references('id')
                ->on('states')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('stock_type_id', 'purchase_details_fk_stock_type_id')
                ->references('id')
                ->on('stock_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_state_id', 'purchase_details_fk_forest_state_id')
                ->references('id')
                ->on('forest_states')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_division_id', 'purchase_details_fk_forest_division_id')
                ->references('id')
                ->on('forest_divisions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_range_id', 'purchase_details_fk_forest_range_id')
                ->references('id')
                ->on('forest_ranges')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_beat_id', 'purchase_details_fk_forest_beat_id')
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
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropForeign('purchase_details_fk_purchase_id');
            $table->dropForeign('purchase_details_fk_product_id');
            $table->dropForeign('purchase_details_fk_nursery_id');
            $table->dropForeign('purchase_details_fk_budget_id');
            $table->dropForeign('purchase_details_fk_division_id');
            $table->dropForeign('purchase_details_fk_district_id');
            $table->dropForeign('purchase_details_fk_upazila_id');
            $table->dropForeign('purchase_details_fk_state_id');
            $table->dropForeign('purchase_details_fk_stock_type_id');

            $table->dropForeign('purchase_details_fk_forest_state_id');
            $table->dropForeign('purchase_details_fk_forest_division_id');
            $table->dropForeign('purchase_details_fk_forest_range_id');
            $table->dropForeign('purchase_details_fk_forest_beat_id');
        });
    }
}
