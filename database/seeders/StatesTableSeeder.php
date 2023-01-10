<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        
        DB::table('states')->truncate();

        DB::insert("INSERT INTO `states` (`id`, `name`, `title_bn`, `title_en`, `bbs_code`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, NULL, 'সেন্ট্রাল সার্কেল , ঢাকা', 'Central Circle, Dhaka', '1', 1, 1, 1, '2022-07-14 00:00:59', '2022-07-14 00:03:06')");
        
        DB::table('forest_states')->truncate();

        DB::insert("INSERT INTO `forest_states` (`id`, `name`, `title_bn`, `title_en`, `bbs_code`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, NULL, 'সেন্ট্রাল সার্কেল , ঢাকা', 'Central Circle, Dhaka', '1', 1, 1, 1, '2022-07-13 18:00:59', '2022-07-13 18:03:06'),
        (2, NULL, 'সামাজিক বনায়ন সার্কেল, ঢাকা', 'Social Forestry Circle, Dhaka', '2', 1, 1, 1, '2022-07-13 18:01:55', '2022-07-13 18:03:15'),
        (3, NULL, 'কোস্টাল সার্কেল, বরিশাল', 'Coastal Circle, Barishal', '3', 1, 1, NULL, '2022-07-13 18:02:52', '2022-07-13 18:02:52'),
        (4, NULL, 'সামাজিক বন সার্কেল, যশোর', 'Social Forest Circle, Jashore', '4', 1, 1, NULL, '2022-07-13 18:04:19', '2022-07-13 18:04:19'),
        (5, NULL, 'খুলনা সার্কেল', 'Khulna Circle', '5', 1, 1, NULL, '2022-07-13 18:05:01', '2022-07-13 18:05:01'),
        (6, NULL, 'চট্টগ্রাম, সার্কেল', 'Chattogram, Circle', '6', 1, 1, NULL, '2022-07-13 18:05:48', '2022-07-13 18:05:48'),
        (7, NULL, 'রাঙামাটি, সার্কেল', 'Rangamati, Circle', '7', 1, 1, NULL, '2022-07-13 18:06:19', '2022-07-13 18:06:19'),
        (8, NULL, 'সামাজিক বন সার্কেল, বগুড়া', 'Social Forest Circle, Bogra', '8', 1, 1, NULL, '2022-07-13 18:07:09', '2022-07-13 18:07:09');");

        Schema::enableForeignKeyConstraints();

    }
}