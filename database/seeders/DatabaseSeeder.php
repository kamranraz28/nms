<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //User::truncate();
        
        

        $this->call([SiteSettingSeeder::class,
        StatesTableSeeder::class,DivisionsTableSeeder::class,DistrictsTableSeeder::class,UpazilasTableSeeder::class,
        UsertypesTableSeeder::class,ProductsTableSeeder::class,AdminsTableSeeder::class]);
        
        //\App\Models\Role::factory(1)->create();
        \App\Models\User::factory(1)->create();
        //\App\Models\Admin::factory(1)->create();
        //\App\Models\Nursery::factory(1)->create();
        
        //$this->call([NurseriesTableSeeder::class]);
        $this->call([InvoicesTableSeeder::class]);
        $this->call([LangsSeeder::class]);
        $this->call([RolesTableSeeder::class]);
        $this->call([RolePermissionSeeder::class]);
        

    }
}
