<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->foreign('content_category_id', 'contents_fk_content_category_id')
                ->references('id')
                ->on('content_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->foreign('version_id', 'contents_fk_version_id')
                ->references('id')
                ->on('versions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

                //$table->foreign('institute_id', 'headers_fk_institute_id')->references('id')->on('institutes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropForeign('contents_fk_content_category_id');
            $table->dropForeign('contents_fk_version_id');
        });
    }
}
