<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('colors')->truncate();
        DB::table('colors')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'code' => '1',
                    'title_en' => 'Black',
                    'title_bn' => 'কালো',
                    'status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
        ));

        DB::table('units')->truncate();
        DB::table('units')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'code' => '1',
                    'title_en' => 'Piece',
                    'title_bn' => 'টুকরা',
                    'status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
        ));

        DB::table('ages')->truncate();
        DB::table('ages')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'code' => '1',
                    'title_en' => '10 Years',
                    'title_bn' => '১০ বছর',
                    'status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
        ));

        DB::table('sizes')->truncate();
        DB::table('sizes')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'code' => '1',
                    'title_en' => '20 Feet',
                    'title_bn' => '২0 ফুট',
                    'status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
        ));

        DB::table('price_types')->truncate();
        DB::table('price_types')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'code' => '1',
                    'title_en' => 'Regular Bag',
                    'title_bn' => 'নিয়মিত ব্যাগ ',
                    'status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            1 =>
                array(
                    'id' => 2,
                    'code' => '2',
                    'title_en' => 'Special Bag',
                    'title_bn' => 'বিশেষ ব্যাগ',
                    'status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            2 =>
                array(
                    'id' => 3,
                    'code' => '3',
                    'title_en' => '10" Tob',
                    'title_bn' => '১০" টব ',
                    'status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            3 =>
                array(
                    'id' => 4,
                    'code' => '4',
                    'title_en' => '12" Tob',
                    'title_bn' => '১২" টব',
                    'status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
        ));

        // DB::table('stock_types')->truncate();
        // DB::table('stock_types')->insert(array(
        //     0 =>
        //         array(
        //             'id' => 1,
        //             'code' => '1',
        //             'title_en' => 'Free',
        //             'title_bn' => 'বিনামূল্যে',
        //             'status' => 1,
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     1 =>
        //         array(
        //             'id' => 2,
        //             'code' => '2',
        //             'title_en' => 'Sales and Distribution',
        //             'title_bn' => 'বিক্রয় ও বিপনন',
        //             'status' => 1,
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     2 =>
        //         array(
        //             'id' => 3,
        //             'code' => '3',
        //             'title_en' => 'Forestry Strip',
        //             'title_bn' => 'বনায়ন স্ট্রিপ',
        //             'status' => 1,
        //             'created_at' => NOW(),
        //             'updated_at' => NOW()
        //         ),
        //     3 =>
        //         array(
        //             'id' => 4,
        //             'code' => '4',
        //             'title_en' => 'Forestry Block',
        //             'title_bn' => 'বনায়ন ব্লক',
        //             'status' => 1,
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        // ));


        // DB::table('products')->truncate();
        // DB::table('products')->insert(array(
        //     0 =>
        //         array(
        //             'id' => 1,
        //             'category_id' => 1,
        //             'unit_id' => 1,
        //             'size_id' => 1,
        //             'age_id' => 1,
        //             'color_id' => 1,
        //             'code' => '1',
        //             'title_en' => 'Abiu Fruit Tree Plant',
        //             'title_bn' => 'আবিউ ফলের গাছের চারা',
        //             'scientific_en' => 'Pouteria caimito',
        //             'scientific_bn' => 'পাউটিরিয়া ক্যামিটো',
        //             'price' => 150000,
        //             'status' => 1,
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        // ));

        DB::table('stock_types')->truncate();
        DB::insert("INSERT INTO `stock_types` (`id`, `code`, `name`, `title_bn`, `title_en`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, '1', NULL, 'বিক্রয়', 'Sales', 1, NULL, 1, '2022-08-16 09:06:59', '2022-08-17 01:51:14'),
        (2, '2', NULL, 'বনায়ন', 'Plantation', 1, NULL, 1, '2022-08-16 09:06:59', '2022-08-17 01:51:35'),
        (3, '3', NULL, 'বিনামূল্যে বিতরন', 'Free Distribution', 1, NULL, 1, '2022-08-16 09:06:59', '2022-08-17 01:52:12'),
        (4, '4', NULL, 'অন্যান্য', 'Others', 1, NULL, 1, '2022-08-16 09:06:59', '2022-08-17 01:52:25')");

        DB::table('budgets')->truncate();
        DB::insert("INSERT INTO `budgets` (`id`, `code`, `year`, `name`, `title_bn`, `title_en`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, '1', 2020, NULL, 'উন্নয়ন বাজেট', 'Development Budget', 1, 1, 1, '2022-07-19 20:12:23', '2022-08-12 00:42:35'),
        (2, '2', 2021, NULL, 'অনুন্নয়ন বাজেট', 'Underdevelopment Budget', 1, 1, 1, '2022-07-19 20:12:49', '2022-08-12 00:43:25')");

        DB::table('categories')->truncate();
        DB::insert("INSERT INTO `categories` (`id`, `code`, `name`, `title_bn`, `title_en`, `parent_id`, `last`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, '1', NULL, 'বনজ', 'Forest plants', NULL, 1, 1, NULL, 1, '2022-06-27 01:32:57', '2022-08-17 01:54:04'),
        (2, '2', NULL, 'ফলজ', 'Fruits Plants', NULL, 1, 1, 1, 1, '2022-06-27 01:47:20', '2022-08-17 01:54:04'),
        (3, '3', NULL, 'ভেষজ', 'Herbs', NULL, 1, 1, 1, NULL, '2022-08-17 01:54:43', '2022-08-17 01:54:43'),
        (4, '4', NULL, 'শোভাবর্ধনকারী', 'Ornamental Plants', NULL, 1, 1, 1, NULL, '2022-08-17 01:55:40', '2022-08-17 01:55:40'),
        (5, '5', NULL, 'অন্যান্য', 'Others', NULL, 1, 1, 1, NULL, '2022-08-17 01:56:02', '2022-08-17 01:56:02')");
        
        DB::table('products')->truncate();
        DB::insert("INSERT INTO `products` (`id`, `category_id`, `unit_id`, `size_id`, `color_id`, `age_id`, `code`, `name`, `title_bn`, `title_en`, `scientific_bn`, `scientific_en`, `details_bn`, `details_en`, `price`, `price_bag`, `price_10`, `price_12`, `percent`, `discount`, `saleable`, `thumb`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, 1, 1, 1, 1, 1, '1', NULL, 'আবিউ ফলের গাছের চারা', 'Abiu Fruit Tree Plant', 'পাউটিরিয়া ক্যামিটো', 'Pouteria caimito', NULL, NULL, 150000.00, 0.00, 0.00, 0.00, 0, 0.00, 1, NULL, 1, NULL, 1, '2022-06-27 07:32:57', '2022-06-27 07:46:08'),
        (2, 1, 1, 1, NULL, 1, '2', NULL, 'আখরোট ফলের গাছ', 'Akhrot Fruit Tree', 'আখরোট ফলের গাছ', 'Akhrot Fruit Tree', 'আখরোট ফলের গাছ', 'Akhrot Fruit Tree', 100.00, 0.00, 0.00, 0.00, 0, 0.00, 1, NULL, 1, 1, NULL, '2022-06-27 07:45:53', '2022-06-27 07:45:53'),
        (3, 2, 1, 1, NULL, 1, '3', NULL, 'মটরশুটি বীজ', 'Beans Seeds', 'মটরশুটি বীজ', 'Beans Seeds', 'মটরশুটি বীজ', 'Beans Seeds', 200.00, 0.00, 0.00, 0.00, 0, 0.00, 1, NULL, 1, 1, NULL, '2022-06-27 07:48:36', '2022-06-27 07:48:36'),
        (4, 2, 1, 1, NULL, 1, '4', NULL, 'বোরবটি বীজ', 'Borboti Seeds', 'বোরবটি বীজ', 'Borboti Seeds', 'বোরবটি বীজ', 'Borboti Seeds', 300.00, 0.00, 0.00, 0.00, 0, 0.00, 1, NULL, 1, 1, NULL, '2022-06-27 07:49:19', '2022-06-27 07:49:19')");

        DB::table('financial_years')->truncate();
        DB::insert("INSERT INTO `financial_years` (`id`, `code`, `year`, `name`, `title_bn`, `title_en`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, '1', 2020, NULL, '২০২০ - ২০২১', '2020 - 2021', 1, 1, 1, '2022-07-19 20:12:23', '2022-08-12 00:42:35'),
        (2, '2', 2021, NULL, '২০২১ - ২০২২', '2021 - 2022', 1, 1, 1, '2022-07-19 20:12:49', '2022-08-12 00:43:25'),
        (3, '3', 2022, NULL, '২০২২ - ২০২৩', '2022 - 2023', 1, 1, 1, '2022-08-12 00:38:48', '2022-08-12 00:43:33')");

        Schema::enableForeignKeyConstraints();

    }
}