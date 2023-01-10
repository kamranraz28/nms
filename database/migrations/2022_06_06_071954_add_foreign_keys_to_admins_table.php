<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            
            $table->foreign('role_id', 'admins_fk_role_id')
                ->references('id')
                ->on('roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_type_id', 'admins_fk_user_type_id')
                ->references('id')
                ->on('user_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            // Should be removed
            $table->foreign('state_id', 'admins_fk_state_id')
                ->references('id')
                ->on('states')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('division_id', 'admins_fk_division_id')
                ->references('id')
                ->on('divisions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('district_id', 'admins_fk_district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('upazila_id', 'admins_fk_upazila_id')
                ->references('id')
                ->on('upazilas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // Should be removed
            
            $table->foreign('forest_state_id', 'admins_fk_forest_state_id')
                ->references('id')
                ->on('forest_states')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_division_id', 'admins_fk_forest_division_id')
                ->references('id')
                ->on('forest_divisions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_range_id', 'admins_fk_forest_range_id')
                ->references('id')
                ->on('forest_ranges')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('forest_beat_id', 'admins_fk_forest_beat_id')
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
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign('admins_fk_role_id');
            $table->dropForeign('admins_fk_user_type_id');
            $table->dropForeign('admins_fk_state_id');
            $table->dropForeign('admins_fk_division_id');
            $table->dropForeign('admins_fk_district_id');
            $table->dropForeign('admins_fk_upazila_id');

            $table->dropForeign('admins_fk_forest_state_id');
            $table->dropForeign('admins_fk_forest_division_id');
            $table->dropForeign('admins_fk_forest_range_id');
            $table->dropForeign('admins_fk_forest_beat_id');

            
        });
    }
}
