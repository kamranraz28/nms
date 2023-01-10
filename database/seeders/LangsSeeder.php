<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lang as ModelsLang;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Schema;

class LangsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        
        DB::table('langs')->truncate();

        $data = [];
        // For lang 1
        //$langs = Lang::get('admin');
        $langs = File::getRequire(base_path().'/resources/lang/en/admin.php');
        foreach ($langs as $key => $value) {
            $data['page'] = $key;
            foreach ($value as $key1 => $value1) {
                $data['key'] = $key1;
                $data['lang_1'] = $value1;
                ModelsLang::create($data);
            }
        }
        
        // // For lang 2
        //$langs = Lang::get('admin');
        $langs = File::getRequire(base_path().'/resources/lang/bn/admin.php');
        foreach ($langs as $key => $value) {
            $data['page'] = $key;
            foreach ($value as $key1 => $value1) {
            DB::table('langs')->where('key', $key1)->update(['lang_2' => $value1]);
            }
        }

        Schema::enableForeignKeyConstraints();
    }

}
