<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InvoicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        
        //Purchase
        DB::table('purchases')->truncate();
        DB::insert("INSERT INTO `purchases` (`id`, `stock_type_id`, `nursery_id`, `budget_id`, `state_id`, `division_id`, `district_id`, `upazila_id`, `forest_state_id`, `forest_division_id`, `forest_range_id`, `forest_beat_id`, `code`, `name`, `year`, `details_bn`, `details_en`, `total`, `percent`, `discount`, `vat`, `app_status`, `approved`, `web`, `status`, `vch_date`, `approved_by`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, 2, NULL, 3, NULL, 3, 20, 152, 1, 3, 20, 152, '1', NULL, 2022, 'Good', 'Good', 0.00, 0, 0.00, 0.00, 1, 0, 0, 1, '2022-08-12', 10, 10, 10, '2022-08-11 02:50:19', '2022-08-11 13:01:05'),
        (3, 2, NULL, 1, NULL, 6, 57, 345, 8, 6, 57, 345, '2', NULL, 2022, NULL, NULL, 0.00, 0, 0.00, 0.00, 1, 0, 1, 1, '2022-08-16', NULL, 1, NULL, '2022-08-16 09:42:46', '2022-08-16 09:42:46'),
        (4, 2, NULL, 1, NULL, 6, 56, 255, 8, 6, 56, 255, '3', NULL, 2021, NULL, NULL, 0.00, 0, 0.00, 0.00, 4, 1, 1, 1, '2021-07-01', 7, 15, 1, '2022-08-16 10:21:34', '2022-08-16 12:12:19'),
        (5, 2, NULL, 1, NULL, 6, 56, 254, 8, 6, 56, 254, '4', NULL, 2023, NULL, NULL, 0.00, 0, 0.00, 0.00, 4, 1, 1, 1, '2023-06-29', 7, 1, NULL, '2022-08-16 12:07:17', '2022-08-16 12:08:53')");

        
        //PurchaseDetail
        DB::table('purchase_details')->truncate();
        DB::insert("INSERT INTO `purchase_details` (`id`, `stock_type_id`, `purchase_id`, `nursery_id`, `budget_id`, `state_id`, `division_id`, `district_id`, `upazila_id`, `forest_state_id`, `forest_division_id`, `forest_range_id`, `forest_beat_id`, `product_id`, `category_id`, `unit_id`, `size_id`, `color_id`, `age_id`, `price_type_id`, `code`, `name`, `year`, `quantity`, `price`, `total`, `app_status`, `approved`, `web`, `status`, `vch_date`, `approved_by`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, 2, 1, NULL, 3, NULL, 3, 20, 152, 1, 3, 20, 152, 1, 1, 1, 1, 1, 1, 1, '3', NULL, 2022, 10, 150000.00, 0.00, 1, 0, 0, 1, '2022-08-12', 10, 10, 10, '2022-08-11 02:50:19', '2022-08-11 13:01:05'),
        (2, 2, 1, NULL, 3, NULL, 3, 20, 152, 1, 3, 20, 152, 2, 1, 1, 1, NULL, 1, 1, '3', NULL, 2022, 20, 100.00, 0.00, 1, 0, 0, 1, '2022-08-12', 10, 10, 10, '2022-08-11 02:50:19', '2022-08-11 13:01:05'),
        (3, 2, 1, NULL, 3, NULL, 3, 20, 152, 1, 3, 20, 152, 3, 2, 1, 1, NULL, 1, 1, '3', NULL, 2022, 30, 200.00, 0.00, 1, 0, 0, 1, '2022-08-12', 10, 10, 10, '2022-08-11 02:50:19', '2022-08-11 13:01:05'),
        (4, 2, 1, NULL, 3, NULL, 3, 20, 152, 1, 3, 20, 152, 4, 2, 1, 1, NULL, 1, 1, '3', NULL, 2022, 40, 300.00, 0.00, 1, 0, 0, 1, '2022-08-12', 10, 10, 10, '2022-08-11 02:50:19', '2022-08-11 13:01:05'),
        (9, 2, 3, NULL, 1, NULL, 6, 57, 345, 8, 6, 57, 345, 1, 1, 1, 1, 1, 1, 1, '5', NULL, 2022, 130, 150000.00, 0.00, 1, 0, 1, 1, '2022-08-16', NULL, 1, NULL, '2022-08-16 09:42:46', '2022-08-16 09:42:46'),
        (10, 2, 3, NULL, 1, NULL, 6, 57, 345, 8, 6, 57, 345, 3, 2, 1, 1, NULL, 1, 1, '5', NULL, 2022, 150, 200.00, 0.00, 1, 0, 1, 1, '2022-08-16', NULL, 1, NULL, '2022-08-16 09:42:46', '2022-08-16 09:42:46'),
        (11, 2, 4, NULL, 1, NULL, 6, 56, 255, 8, 6, 56, 255, 1, 1, 1, 1, 1, 1, 1, '8', NULL, 2021, 450, 150000.00, 0.00, 4, 1, 1, 1, '2021-07-01', 7, 15, 1, '2022-08-16 10:21:34', '2022-08-16 12:12:19'),
        (12, 2, 4, NULL, 1, NULL, 6, 56, 255, 8, 6, 56, 255, 3, 2, 1, 1, NULL, 1, 1, '8', NULL, 2021, 550, 200.00, 0.00, 4, 1, 1, 1, '2021-07-01', 7, 15, 1, '2022-08-16 10:21:34', '2022-08-16 12:12:19'),
        (13, 2, 5, NULL, 1, NULL, 6, 56, 254, 8, 6, 56, 254, 1, 1, 1, 1, 1, 1, 1, '7', NULL, 2023, 560, 150000.00, 0.00, 4, 1, 1, 1, '2023-06-29', 7, 1, NULL, '2022-08-16 12:07:17', '2022-08-16 12:07:17'),
        (14, 2, 5, NULL, 1, NULL, 6, 56, 254, 8, 6, 56, 254, 4, 2, 1, 1, NULL, 1, 1, '7', NULL, 2023, 450, 300.00, 0.00, 4, 1, 1, 1, '2023-06-29', 7, 1, NULL, '2022-08-16 12:07:17', '2022-08-16 12:07:17')");

        
        //Sale
        DB::table('sales')->truncate();
        DB::insert("INSERT INTO `sales` (`id`, `stock_type_id`, `user_id`, `nursery_id`, `budget_id`, `state_id`, `division_id`, `district_id`, `upazila_id`, `forest_state_id`, `forest_division_id`, `forest_range_id`, `forest_beat_id`, `code`, `name`, `year`, `details_bn`, `details_en`, `total`, `vat`, `vat_amount`, `percent`, `discount`, `discount_amount`, `total_amount`, `free`, `walking`, `app_status`, `approved`, `web`, `status`, `vch_date`, `approved_by`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, 2, 1, NULL, 3, NULL, 3, 20, 152, 1, 3, 20, 152, '1', NULL, 2022, 'Good', 'Good', 760000.00, 0.00, 0.00, 0, 0.00, 0.00, 760000.00, 0, 0, 1, 0, 0, 1, '2022-08-12', 10, 10, 10, '2022-08-11 02:58:09', '2022-08-11 13:01:26'),
        (2, 2, 1, NULL, 3, NULL, 6, 54, 145, 8, 6, 54, 145, '2', NULL, 2022, NULL, NULL, 10250.00, 0.00, 0.00, 0, 0.00, 0.00, 10250.00, 0, 0, 1, 0, 1, 1, '2022-08-13', NULL, 12, NULL, '2022-08-12 20:33:03', '2022-08-12 20:33:03'),
        (3, 2, 1, NULL, 1, NULL, 6, 56, 255, 8, 6, 56, 255, '3', NULL, 2022, NULL, NULL, 990.00, 0.00, 0.00, 0, 0.00, 0.00, 990.00, 0, 0, 4, 1, 1, 1, '2022-08-16', 7, 1, NULL, '2022-08-16 11:03:29', '2022-08-16 11:07:09')");
        
        //SaleDetail
        DB::table('sale_details')->truncate();
        DB::insert("INSERT INTO `sale_details` (`id`, `stock_type_id`, `user_id`, `sale_id`, `nursery_id`, `budget_id`, `state_id`, `division_id`, `district_id`, `upazila_id`, `forest_state_id`, `forest_division_id`, `forest_range_id`, `forest_beat_id`, `product_id`, `category_id`, `unit_id`, `size_id`, `color_id`, `age_id`, `price_type_id`, `code`, `name`, `year`, `quantity`, `price`, `total`, `free`, `walking`, `app_status`, `approved`, `web`, `status`, `vch_date`, `approved_by`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, 2, 1, 1, NULL, 3, NULL, 3, 20, 152, 1, 3, 20, 152, 1, 1, 1, 1, 1, 1, NULL, '4', NULL, 2022, 5, 150000.00, 750000.00, 0, 0, 1, 0, 0, 1, '2022-08-12', 10, 10, 10, '2022-08-11 02:58:09', '2022-08-11 13:01:26'),
        (2, 2, 1, 1, NULL, 3, NULL, 3, 20, 152, 1, 3, 20, 152, 2, 1, 1, 1, NULL, 1, 1, '4', NULL, 2022, 10, 100.00, 1000.00, 0, 0, 1, 0, 0, 1, '2022-08-12', 10, 10, 10, '2022-08-11 02:58:09', '2022-08-11 13:01:26'),
        (3, 2, 1, 1, NULL, 3, NULL, 3, 20, 152, 1, 3, 20, 152, 3, 2, 1, 1, NULL, 1, 1, '4', NULL, 2022, 15, 200.00, 3000.00, 0, 0, 1, 0, 0, 1, '2022-08-12', 10, 10, 10, '2022-08-11 02:58:09', '2022-08-11 13:01:26'),
        (4, 2, 1, 1, NULL, 3, NULL, 3, 20, 152, 1, 3, 20, 152, 4, 2, 1, 1, NULL, 1, 1, '4', NULL, 2022, 20, 300.00, 6000.00, 0, 0, 1, 0, 0, 1, '2022-08-12', 10, 10, 10, '2022-08-11 02:58:09', '2022-08-11 13:01:26'),
        (5, 2, 1, 2, NULL, 3, NULL, 6, 54, 145, 8, 6, 54, 145, 1, 1, 1, 1, 1, 1, NULL, '5', NULL, 2022, 5, 50.00, 250.00, 0, 0, 1, 0, 1, 1, '2022-08-13', NULL, 12, NULL, '2022-08-12 20:33:03', '2022-08-12 20:33:03'),
        (6, 2, 1, 2, NULL, 3, NULL, 6, 54, 145, 8, 6, 54, 145, 2, 1, 1, 1, NULL, 1, NULL, '5', NULL, 2022, 10, 100.00, 1000.00, 0, 0, 1, 0, 1, 1, '2022-08-13', NULL, 12, NULL, '2022-08-12 20:33:03', '2022-08-12 20:33:03'),
        (7, 2, 1, 2, NULL, 3, NULL, 6, 54, 145, 8, 6, 54, 145, 3, 2, 1, 1, NULL, 1, NULL, '5', NULL, 2022, 15, 200.00, 3000.00, 0, 0, 1, 0, 1, 1, '2022-08-13', NULL, 12, NULL, '2022-08-12 20:33:03', '2022-08-12 20:33:03'),
        (8, 2, 1, 2, NULL, 3, NULL, 6, 54, 145, 8, 6, 54, 145, 4, 2, 1, 1, NULL, 1, NULL, '5', NULL, 2022, 20, 300.00, 6000.00, 0, 0, 1, 0, 1, 1, '2022-08-13', NULL, 12, NULL, '2022-08-12 20:33:03', '2022-08-12 20:33:03'),
        (9, 2, 1, 3, NULL, 1, NULL, 6, 56, 255, 8, 6, 56, 255, 1, 1, 1, 1, 1, 1, NULL, '6', NULL, 2022, 60, 9.00, 540.00, 0, 0, 4, 1, 1, 1, '2022-08-16', 7, 1, NULL, '2022-08-16 11:03:29', '2022-08-16 11:03:29'),
        (10, 2, 1, 3, NULL, 1, NULL, 6, 56, 255, 8, 6, 56, 255, 4, 2, 1, 1, NULL, 1, NULL, '6', NULL, 2022, 50, 9.00, 450.00, 0, 0, 4, 1, 1, 1, '2022-08-16', 7, 1, NULL, '2022-08-16 11:03:29', '2022-08-16 11:03:29')");
       
        Schema::enableForeignKeyConstraints();

    }
}