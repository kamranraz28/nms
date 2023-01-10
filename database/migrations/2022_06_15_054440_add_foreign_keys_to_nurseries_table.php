<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNurseriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nurseries', function (Blueprint $table) {
            $table->foreign('admin_id', 'nurseries_fk_admin_id')
                ->references('id')
                ->on('admins')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('division_id', 'nurseries_fk_division_id')
                ->references('id')
                ->on('divisions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('district_id', 'nurseries_fk_district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('upazila_id', 'nurseries_fk_upazila_id')
                ->references('id')
                ->on('upazilas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('state_id', 'nurseries_fk_state_id')
                ->references('id')
                ->on('states')
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
        Schema::table('nurseries', function (Blueprint $table) {
            $table->dropForeign('nurseries_fk_admin_id');
            $table->dropForeign('nurseries_fk_division_id');
            $table->dropForeign('nurseries_fk_district_id');
            $table->dropForeign('nurseries_fk_upazila_id');
            $table->dropForeign('nurseries_fk_state_id');
        });
    }
}
