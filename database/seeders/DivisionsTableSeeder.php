<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DivisionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        
        DB::table('divisions')->truncate();

        DB::table('divisions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title_en' => 'Barisal',
                'title_bn' => 'বরিশাল',
                'bbs_code' => '10',
                'status' => 1,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => '2015-11-17 06:01:41',
                'updated_at' => '2016-02-09 14:06:15',
            ),
            1 => 
            array (
                'id' => 2,
                'title_en' => 'Chittagong',
                'title_bn' => 'চট্টগ্রাম',
                'bbs_code' => '20',
                'status' => 1,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => '2015-11-17 06:01:41',
                'updated_at' => '2016-02-09 14:06:15',
            ),
            2 => 
            array (
                'id' => 3,
                'title_en' => 'Dhaka',
                'title_bn' => 'ঢাকা',
                'bbs_code' => '30',
                'status' => 1,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => '2015-11-17 06:01:41',
                'updated_at' => '2016-02-09 14:06:15',
            ),
            3 => 
            array (
                'id' => 4,
                'title_en' => 'Khulna',
                'title_bn' => 'খুলনা',
                'bbs_code' => '40',
                'status' => 1,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => '2015-11-17 06:01:41',
                'updated_at' => '2016-02-09 14:06:15',
            ),
            4 => 
            array (
                'id' => 5,
                'title_en' => 'Rajshahi',
                'title_bn' => 'রাজশাহী',
                'bbs_code' => '50',
                'status' => 1,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => '2015-11-17 06:01:41',
                'updated_at' => '2016-02-09 14:06:15',
            ),
            5 => 
            array (
                'id' => 6,
                'title_en' => 'Rangpur',
                'title_bn' => 'রংপুর',
                'bbs_code' => '60',
                'status' => 1,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => '2015-11-17 06:01:41',
                'updated_at' => '2016-02-09 14:06:15',
            ),
            6 => 
            array (
                'id' => 7,
                'title_en' => 'Sylhet',
                'title_bn' => 'সিলেট',
                'bbs_code' => '70',
                'status' => 1,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => '2015-11-17 06:01:41',
                'updated_at' => '2016-02-09 14:06:15',
            ),
            7 => 
            array (
                'id' => 9,
                'title_en' => 'Mymensingh',
                'title_bn' => 'ময়মনসিংহ',
                'bbs_code' => '45',
                'status' => 1,
                'created_by' => null,
                'updated_by' => null,
                'created_at' => '2015-11-17 06:01:41',
                'updated_at' => '2016-02-09 14:06:15',
            ),
        ));

        DB::table('forest_divisions')->truncate();
        DB::insert("INSERT INTO `forest_divisions` (`id`, `forest_state_id`, `name`, `title_bn`, `title_en`, `bbs_code`, `price_type`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, 3, NULL, 'সামাজিক বন বিভাগ বরিশাল', 'Social Forest Division Barishal', '10', 1, 1, NULL, 1, '2015-11-16 12:01:41', '2022-07-14 01:06:57'),
        (2, 6, NULL, 'চট্টগ্রাম উত্তর বন বিভাগ', 'Chattogram North Forest Division', '20', 1, 1, NULL, 1, '2015-11-16 12:01:41', '2022-07-14 01:09:45'),
        (3, 1, NULL, 'ঢাকা  বন বিভাগ', 'Dhaka Forest Division', '30', 1, 1, NULL, 1, '2015-11-16 12:01:41', '2022-07-14 01:10:32'),
        (4, 5, NULL, 'খুলনা', 'Khulna', '40', 1, 1, NULL, 1, '2015-11-16 12:01:41', '2022-07-14 01:12:01'),
        (5, 8, NULL, 'সামাজিক বন বিভাগ রাজশাহী', 'Social Forest Division Rajshahi', '95', 1, 1, NULL, 1, '2015-11-16 12:01:41', '2022-07-14 01:13:17'),
        (6, 8, NULL, 'সামাজিক বন বিভাগ রংপুর', 'Social Forest Division Rangpur', '60', 1, 1, NULL, 1, '2015-11-16 12:01:41', '2022-07-14 01:14:11'),
        (7, 1, NULL, 'সিলেট বন বিভাগ', 'Sylhet Forest  Division', '70', 1, 1, NULL, 1, '2015-11-16 12:01:41', '2022-07-14 01:14:52'),
        (9, 1, NULL, 'ময়মনসিংহ বন বিভাগ', 'Mymensingh Forest Division', '45', 1, 1, NULL, 1, '2015-11-16 12:01:41', '2022-07-14 01:15:19'),
        (10, 1, NULL, 'টাঙ্গাইল বন বিভাগ', 'TANGAIL Forest Division', '80', 1, 1, 1, 1, '2022-06-17 13:14:53', '2022-07-14 01:15:36'),
        (11, 8, NULL, 'সামাজিক বন বিভাগ দিনাজপুর', 'Social Forest Division Dinajpur', '50', 1, 1, 1, 1, '2022-06-17 13:17:45', '2022-07-14 01:16:04'),
        (12, 8, NULL, 'সামাজিক বন বিভাগ বগুড়া', 'Social Forest Division Bogura', '65', 1, 1, 1, 1, '2022-06-17 15:11:29', '2022-07-14 01:18:45'),
        (13, 8, NULL, 'সামাজিক বন বিভাগ পাবনা', 'Social Forest Division Pabna', '75', 1, 1, 1, 1, '2022-06-17 15:12:29', '2022-07-14 01:16:17'),
        (14, 2, NULL, 'সামাজিক বন বিভাগ ঢাকা', 'Social Forest Division Dhaka', '35', 1, 1, 1, 1, '2022-06-17 15:14:41', '2022-07-14 01:16:51'),
        (15, 2, NULL, 'সামাজিক বন বিভাগ কুমিল্লা', 'Social Forest Division Cumilla', '55', 1, 1, 1, 1, '2022-06-17 15:16:00', '2022-07-14 01:17:54'),
        (16, 2, NULL, 'সামাজিক বন বিভাগ ফেনী', 'Social Forest Division Feni', '85', 1, 1, 1, 1, '2022-06-17 15:16:48', '2022-07-14 01:18:17'),
        (17, 1, NULL, 'জাতীয় উদ্ভিদ উদ্যান', 'National Botanical Garden', '90', 1, 1, 1, 1, '2022-06-17 15:18:43', '2022-07-14 01:20:16'),
        (18, 4, NULL, 'সামাজিক বন বিভাগ যশোর', 'Social Forest Division Jashore', '46', 1, 1, 1, 1, '2022-06-17 15:20:21', '2022-07-14 01:18:37'),
        (19, 4, NULL, 'সামাজিক বন বিভাগ কুষ্টিয়া', 'Social Forest Division Kushtia', '15', 1, 1, 1, 1, '2022-06-17 15:21:29', '2022-07-14 01:19:12'),
        (20, 4, NULL, 'সামাজিক বন বিভাগ বাগেরহাট', 'Social Forest Division Bagerhat', '25', 1, 1, 1, 1, '2022-06-17 15:22:22', '2022-07-14 01:21:45'),
        (21, 4, NULL, 'সামাজিক বন বিভাগ ফরিদপুর', 'Social Forest Division Faridpur', '93', 1, 1, 1, 1, '2022-06-17 15:26:20', '2022-07-14 01:22:04'),
        (22, 7, NULL, 'খাগড়াছড়ি বন বিভাগ', 'Khagrachhari Forest Division', '83', 1, 1, 1, 1, '2022-06-17 15:27:15', '2022-07-14 01:23:25'),
        (23, 7, NULL, 'ঝুম নিয়ন্ত্রণ বন বিভাগ', 'Jhum Control Forest Division', '86', 1, 1, 1, 1, '2022-06-17 15:28:19', '2022-07-14 01:23:47'),
        (24, 6, NULL, 'চট্টগ্রাম দক্ষিণ বন বিভাগ', 'Chattogram South Forest Division', '23', 1, 1, 1, 1, '2022-06-17 15:30:13', '2022-07-14 01:24:22'),
        (25, 6, NULL, 'কক্সবাজার উত্তর বন বিভাগ', 'Cox Bazar North Forest Division', '73', 1, 1, 1, 1, '2022-06-17 15:31:28', '2022-07-14 01:24:32'),
        (26, 6, NULL, 'কক্সবাজার দক্ষিণ বন বিভাগ', 'Cox Bazar South Forest Division', '76', 1, 1, 1, 1, '2022-06-17 15:32:05', '2022-07-14 01:24:50'),
        (27, 6, NULL, 'বান্দরবান বন বিভাগ', 'Bandarban Forest Division', '63', 1, 1, 1, 1, '2022-06-17 15:32:40', '2022-07-14 01:25:01'),
        (28, 6, NULL, 'লামা বন বিভাগ', 'Lama Forest Division', '13', 1, 1, 1, 1, '2022-06-17 15:34:22', '2022-07-14 01:25:18'),
        (29, 3, NULL, 'উপকূলী বন বিভাগ', 'Coastal Forest Division', '26', 1, 1, 1, 1, '2022-06-17 15:35:09', '2022-07-14 01:26:24'),
        (30, 3, NULL, 'উপকূলীয় বন বিভাগ নোয়াখালী', 'Coastal Forest Division Noakhali', '33', 1, 1, 1, 1, '2022-06-17 15:36:21', '2022-07-14 01:26:52'),
        (31, 3, NULL, 'উপকূলীয় বন বিভাগ ভোলা', 'Coastal Forest Division Bhola', '36', 1, 1, 1, 1, '2022-06-17 15:37:14', '2022-07-14 01:27:05'),
        (32, 3, NULL, 'উপকূলীয় বন বিভাগ পটুয়াখালী', 'Coastal Forest Division Patuakhali', '43', 1, 1, 1, 1, '2022-06-17 15:38:02', '2022-07-14 01:27:12')");

        Schema::enableForeignKeyConstraints();

    }
}