<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('site_title', 191)->default('YOUTH ENROLLMENT AND CERTIFICATION')->nullable();
            $table->string('site_web', 191)->default('http://bforest.portal.gov.bd/')->nullable();
            $table->string('site_email', 191)->default('info@bforest.gov.bd')->nullable();
            $table->string('site_mobile', 191)->default('+88-02-8181737')->nullable();
            $table->mediumText('site_address_en', 191)->default('BanBhaban,Plot No: E - 8, B - 2,Sherebangla Nagar,Agargaon ')->nullable();
            $table->mediumText('site_address_bn', 191)->default('বনভবন, প্লট নং: ই - 8, বি - 2, শেরেবাংলা নগর, আগারগাঁও')->nullable();
            // $table->mediumText('site_description')->default('Lorem Ipsum is simply dummy text of the printing and typesetting industry.')->nullable();
            
            // $table->string('site_logo')->nullable();
            // $table->string('site_favicon')->nullable();

            // $table->string('local_currency', 191)->default('USD')->nullable();
            // $table->string('locale', 191)->default('en-US')->nullable();
            // $table->string('locale_code', 191)->default('en')->nullable();
            // $table->string('locale_symble', 191)->default('$')->nullable();

            // $table->mediumText('meta_tag')->nullable();
            // $table->mediumText('meta_name')->nullable();
            // $table->mediumText('meta_description')->nullable();

            // $table->tinyInteger('show_slider')->default(1);
            // $table->tinyInteger('show_gallary')->default(1);
            // $table->tinyInteger('show_lang')->default(0);
            // $table->tinyInteger('show_logo')->default(0);
            // $table->tinyInteger('show_favicon')->default(0);
            // $table->tinyInteger('row_status')->default(1);
            
            $table->string('mailer', 64)->default('smtp')->nullable();
            $table->string('host', 64)->default('mail.dpg.gov.bd')->nullable();
            $table->smallInteger('port')->default(587)->nullable();
            $table->string('username', 64)->default('noreply@dpg.gov.bd')->nullable();
            $table->string('password', 64)->default('2RoPyzaY')->nullable();
            $table->string('encryption', 64)->default('tls')->nullable();
            
            
            $table->tinyInteger('layout')->default(0);
            $table->float('vat')->nullable()->default(0);


            $table->string('name',128)->default('Bangladesh Forest Department-বন অধিদপ্তর')->nullable();
            $table->string('title_en',512)->default('Bangladesh Forest Department')->nullable();
            $table->string('title_bn',512)->default('বন অধিদপ্তর')->nullable();
            $table->mediumText('footer_en')->default('Copyright © 2020 -- 2022 Bangladesh Forest Department,All rights reserved.')->nullable();
            $table->mediumText('footer_bn')->default('বন অধিদপ্তর জন্য কপিরাইট © 2020 -- 2022 ডকুমেন্টেশন। সমস্ত অধিকার সংরক্ষিত.')->nullable();
            $table->string('favicon',128)->nullable();
            $table->string('logo',128)->nullable();
            $table->string('footer_logo',128)->nullable();
            $table->smallInteger('sort')->default(1);
            $table->tinyInteger('status')->default(1);


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
        Schema::dropIfExists('site_settings');
    }
}
