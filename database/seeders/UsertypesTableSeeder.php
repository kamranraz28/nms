<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsertypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('user_types')->truncate();
        DB::table('user_types')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'sort' => '1',
                    'code' => '1',
                    'name' => 'Master Type',
                    'title_en' => 'Master Admin',
                    'title_bn' => 'মাস্টার এডমিন',
                    'status' => 1,
                    'parent_id' => null,
                    'default_role' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            1 =>
                array(
                    'id' => 2,
                    'sort' => '2',
                    'code' => '2',
                    'name' => 'CCF',
                    'title_en' => 'CCF',
                    'title_bn' => 'সিসিএফ',
                    'status' => 1,
                    'parent_id' => null,
                    'default_role' => 2,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            2 =>
                array(
                    'id' => 3,
                    'sort' => '3',
                    'code' => '3',
                    'name' => 'DCCF',
                    'title_en' => 'DCCF',
                    'title_bn' => 'ডিসিসিএফ',
                    'status' => 1,
                    'parent_id' => null,
                    'default_role' => 2,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            3 =>
                array(
                    'id' => 4,
                    'sort' => '4',
                    'code' => '4',
                    'name' => 'CF',
                    'title_en' => 'CF',
                    'title_bn' => 'সিএফ',
                    'status' => 1,
                    'parent_id' => null,
                    'default_role' => 3,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            4 =>
                array(
                    'id' => 5,
                    'sort' => '5',
                    'code' => '5',
                    'name' => 'DCF',
                    'title_en' => 'DCF',
                    'title_bn' => 'ডিসিএফ',
                    'parent_id' => null,
                    'status' => 1,
                    'default_role' => 2,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            5 =>
                array(
                    'id' => 6,
                    'sort' => '6',
                    'code' => '6',
                    'name' => 'ACCF',
                    'title_en' => 'ACCF',
                    'title_bn' => 'এসিসিএফ',
                    'status' => 1,
                    'parent_id' => null,
                    'default_role' => 2,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            6 =>
                array(
                    'id' => 7,
                    'sort' => '7',
                    'code' => '7',
                    'name' => 'DFO',
                    'title_en' => 'DFO',
                    'title_bn' => 'ডিএফও',
                    'status' => 1,
                    'parent_id' => null,
                    'default_role' => 2,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            7 =>
                array(
                    'id' => 8,
                    'sort' => '8',
                    'code' => '8',
                    'name' => 'ACF',
                    'title_en' => 'ACF',
                    'title_bn' => 'এসিএফ',
                    'status' => 1,
                    'parent_id' => null,
                    'default_role' => 4,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            8 =>
                array(
                    'id' => 9,
                    'sort' => '9',
                    'code' => '9',
                    'name' => 'Range/SFNTC Wise',
                    'title_en' => 'Range Office',
                    'title_bn' => 'রেঞ্জ অফিস',
                    'status' => 1,
                    'parent_id' => null,
                    'default_role' => 5,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            9 =>
                array(
                    'id' => 10,
                    'sort' => '10',
                    'code' => '10',
                    'name' => 'Beat/SFPC Wise',
                    'title_en' => 'Beat Office',
                    'title_bn' => 'বীট অফিস',
                    'status' => 1,
                    'parent_id' => null,
                    'default_role' => 6,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
        ));
        

        Schema::enableForeignKeyConstraints();

    }
}