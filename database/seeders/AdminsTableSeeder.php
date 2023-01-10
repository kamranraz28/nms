<?php
namespace Database\Seeders;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('admins')->truncate();
        // DB::table('admins')->insert(array(
        //     0 =>
        //         array(
        //             'id' => 1,
        //             'code' => 1,
        //             'user_type_id' => 1,
        //             'role_id' => 1,
        //             'division_id' => 3,
        //             'district_id' => 18,
        //             'upazila_id' => 117,
        //             'name' => 'Mr.Admin',
        //             'title_en' => 'Mr.Admin',
        //             'title_bn' => 'মিস্টার এডমিন',
        //             'office_en' => 'Master Admin Office',
        //             'office_bn' => 'মাস্টার অ্যাডমিন অফিস',
        //             'address_en' => 'Master Admin Office, West Agargaon, Dhaka',
        //             'address_bn' => 'মাস্টার অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
        //             'contact' => '01700000000',
        //             'username' => 'admin@bforest.gov.bd',
        //             'email' => 'admin@bforest.gov.bd',
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('password'), // password
        //             'remember_token' => Str::random(10),
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     1 =>
        //         array(
        //             'id' => 2,
        //             'code' => 2,
        //             'user_type_id' => 2,
        //             'role_id' => 2,
        //             'division_id' => 3,
        //             'district_id' => 18,
        //             'upazila_id' => 117,
        //             'name' => 'Mr.CCF',
        //             'title_en' => 'Mr.CCF',
        //             'title_bn' => 'সিসিফ এডমিন',
        //             'office_en' => 'CCF Admin Office',
        //             'office_bn' => 'সিসিফ অ্যাডমিন অফিস',
        //             'address_en' => 'CCF Admin Office, West Agargaon, Dhaka',
        //             'address_bn' => 'সিসিফ অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
        //             'contact' => '01700000000',
        //             'username' => 'ccf@bforest.gov.bd',
        //             'email' => 'ccf@bforest.gov.bd',
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('password'), // password
        //             'remember_token' => Str::random(10),
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     2 =>
        //         array(
        //             'id' => 3,
        //             'code' => 3,
        //             'user_type_id' => 3,
        //             'role_id' => 3,
        //             'division_id' => 3,
        //             'district_id' => 18,
        //             'upazila_id' => 117,
        //             'name' => 'Mr.DCCF',
        //             'title_en' => 'Mr.DCCF',
        //             'title_bn' => 'ডিসিসিফ এডমিন',
        //             'office_en' => 'DCCF Admin Office',
        //             'office_bn' => 'ডিসিসিফ অ্যাডমিন অফিস',
        //             'address_en' => 'DCCF Admin Office, West Agargaon, Dhaka',
        //             'address_bn' => 'ডিসিসিফ অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
        //             'contact' => '01700000000',
        //             'username' => 'dccf@bforest.gov.bd',
        //             'email' => 'dccf@bforest.gov.bd',
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('password'), // password
        //             'remember_token' => Str::random(10),
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     3 =>
        //         array(
        //             'id' => 4,
        //             'code' => 4,
        //             'user_type_id' => 4,
        //             'role_id' => 4,
        //             'division_id' => 3,
        //             'district_id' => 18,
        //             'upazila_id' => 117,
        //             'name' => 'Mr.CF',
        //             'title_en' => 'Mr.CF',
        //             'title_bn' => 'সিফ এডমিন',
        //             'office_en' => 'CF Admin Office',
        //             'office_bn' => 'সিফ অ্যাডমিন অফিস',
        //             'address_en' => 'CF Admin Office, West Agargaon, Dhaka',
        //             'address_bn' => 'সিফ অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
        //             'contact' => '01700000000',
        //             'username' => 'dcf@bforest.gov.bd',
        //             'email' => 'dcf@bforest.gov.bd',
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('password'), // password
        //             'remember_token' => Str::random(10),
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     4 =>
        //         array(
        //             'id' => 5,
        //             'code' => 5,
        //             'user_type_id' => 5,
        //             'role_id' => 5,
        //             'division_id' => 3,
        //             'district_id' => 18,
        //             'upazila_id' => 117,
        //             'name' => 'Mr.DCF',
        //             'title_en' => 'Mr.DCF',
        //             'title_bn' => 'ডিসিফ এডমিন',
        //             'office_en' => 'DCF Admin Office',
        //             'office_bn' => 'ডিসিফ অ্যাডমিন অফিস',
        //             'address_en' => 'DCF Admin Office, West Agargaon, Dhaka',
        //             'address_bn' => 'ডিসিফ অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
        //             'contact' => '01700000000',
        //             'username' => 'cf@bforest.gov.bd',
        //             'email' => 'cf@bforest.gov.bd',
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('password'), // password
        //             'remember_token' => Str::random(10),
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     5 =>
        //         array(
        //             'id' => 6,
        //             'code' => 6,
        //             'user_type_id' => 6,
        //             'role_id' => 6,
        //             'division_id' => 3,
        //             'district_id' => 18,
        //             'upazila_id' => 117,
        //             'name' => 'Mr.ACCF',
        //             'title_en' => 'Mr.ACCF',
        //             'title_bn' => 'এসিসিএফ এডমিন',
        //             'office_en' => 'ACCF Admin Office',
        //             'office_bn' => 'এসিসিএফ অ্যাডমিন অফিস',
        //             'address_en' => 'ACCF Admin Office, West Agargaon, Dhaka',
        //             'address_bn' => 'এসিসিএফ অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
        //             'contact' => '01700000000',
        //             'username' => 'accf@bforest.gov.bd',
        //             'email' => 'accf@bforest.gov.bd',
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('password'), // password
        //             'remember_token' => Str::random(10),
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     6 =>
        //         array(
        //             'id' => 7,
        //             'code' => 7,
        //             'user_type_id' => 7,
        //             'role_id' => 7,
        //             'division_id' => 3,
        //             'district_id' => 18,
        //             'upazila_id' => 117,
        //             'name' => 'Mr.DFO',
        //             'title_en' => 'Mr.DFO',
        //             'title_bn' => 'মিস্টার ডিএফও',
        //             'office_en' => 'DFO Admin Office',
        //             'office_bn' => 'ডিএফও অ্যাডমিন অফিস',
        //             'address_en' => 'DFO Admin Office, West Agargaon, Dhaka',
        //             'address_bn' => 'ডিএফও অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
        //             'contact' => '01700000000',
        //             'username' => 'dfo@bforest.gov.bd',
        //             'email' => 'dfo@bforest.gov.bd',
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('password'), // password
        //             'remember_token' => Str::random(10),
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     7 =>
        //         array(
        //             'id' => 8,
        //             'code' => 8,
        //             'user_type_id' => 8,
        //             'role_id' => 8,
        //             'division_id' => 3,
        //             'district_id' => 18,
        //             'upazila_id' => 117,
        //             'name' => 'Mr.ACFI',
        //             'title_en' => 'Mr.ACFI',
        //             'title_bn' => 'মিস্টার এসিএফআই',
        //             'office_en' => 'ACFI Admin Office',
        //             'office_bn' => 'এসিএফআই অ্যাডমিন অফিস',
        //             'address_en' => 'ACFI Admin Office, West Agargaon, Dhaka',
        //             'address_bn' => 'এসিএফআই অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
        //             'contact' => '01700000000',
        //             'username' => 'acfi@bforest.gov.bd',
        //             'email' => 'acfi@bforest.gov.bd',
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('password'), // password
        //             'remember_token' => Str::random(10),
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     8 =>
        //         array(
        //             'id' => 9,
        //             'code' => 9,
        //             'user_type_id' => 9,
        //             'role_id' => 9,
        //             'division_id' => 3,
        //             'district_id' => 18,
        //             'upazila_id' => 117,
        //             'name' => 'Mr.Range Office',
        //             'title_en' => 'Mr.Range Office',
        //             'title_bn' => 'মিস্টার রেঞ্জ অফিস',
        //             'office_en' => 'Range Office Admin Office',
        //             'office_bn' => 'রেঞ্জ অ্যাডমিন অফিস',
        //             'address_en' => 'Range Office Admin Office, West Agargaon, Dhaka',
        //             'address_bn' => 'রেঞ্জ অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
        //             'contact' => '01700000000',
        //             'username' => 'rangeoffice@bforest.gov.bd',
        //             'email' => 'rangeoffice@bforest.gov.bd',
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('password'), // password
        //             'remember_token' => Str::random(10),
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     9 =>
        //         array(
        //             'id' => 10,
        //             'code' => 10,
        //             'user_type_id' => 10,
        //             'role_id' => 10,
        //             'division_id' => 3,
        //             'district_id' => 18,
        //             'upazila_id' => 117,
        //             'name' => 'Mr.Beat Office',
        //             'title_en' => 'Mr.Beat Office',
        //             'title_bn' => 'মিস্টার বীট অফিস',
        //             'office_en' => 'Beat Admin Office',
        //             'office_bn' => 'বীট অ্যাডমিন অফিস',
        //             'address_en' => 'Beat Admin Office, West Agargaon, Dhaka',
        //             'address_bn' => 'বীট অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
        //             'contact' => '01700000000',
        //             'username' => 'beatoffice@bforest.gov.bd',
        //             'email' => 'beatoffice@bforest.gov.bd',
        //             'email_verified_at' => now(),
        //             'password' => Hash::make('password'), // password
        //             'remember_token' => Str::random(10),
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        // ));
        
        DB::insert("INSERT INTO `admins` (`id`, `parent_id`, `user_type_id`, `role_id`, `state_id`, `division_id`, `district_id`, `upazila_id`, `forest_state_id`, `forest_division_id`, `forest_range_id`, `forest_beat_id`, `code`, `name`, `title_bn`, `title_en`, `office_bn`, `office_en`, `address_bn`, `address_en`, `contact`, `username`, `email`, `email_verified_at`, `thumb`, `remember_token`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
        (1, NULL, 1, 1, NULL, NULL, NULL, NULL, 1, 3, 20, 152, '1', 'Mr.Admin', 'গাজীপুর সদর', 'GAZIPUR SADAR', 'গাজীপুর সদর', 'GAZIPUR SADAR', 'গাজীপুর সদর', 'GAZIPUR SADAR', '01*********', 'admin@bforest.gov.bd', 'admin@bforest.gov.bd', '2022-06-21 11:25:36', NULL, 'dQJQAVwof4pjLGBjDzodGWM3S0eQ97NaTK7fNzQM7DbkZfPmsaUxK6XLwPcA', 1, NULL, 1, '2022-06-21 11:25:36', '2022-07-17 00:49:39'),
        (2, NULL, 2, 2, NULL, NULL, NULL, NULL, 1, 3, 20, 153, '2', 'Mr.CCF', 'কালিয়াকৈর', 'KALIAKAIR', 'কালিয়াকৈর', 'KALIAKAIR', 'কালিয়াকৈর', 'KALIAKAIR', '01*********', 'ccf@bforest.gov.bd', 'ccf@bforest.gov.bd', '2022-06-21 11:25:36', NULL, 'dHJO5c5CC6', 1, NULL, 1, '2022-06-21 11:25:36', '2022-07-17 00:51:39'),
        (3, NULL, 3, 3, NULL, NULL, NULL, NULL, 1, 3, 20, 154, '3', 'Mr.DCCF', 'কালীগঞ্জ', 'KALIGANJ', 'কালীগঞ্জ', 'KALIGANJ', 'কালীগঞ্জ', 'KALIGANJ', '01*********', 'dccf@bforest.gov.bd', 'dccf@bforest.gov.bd', '2022-06-21 11:25:36', NULL, 'fNEiq5GbAW', 1, NULL, 1, '2022-06-21 11:25:36', '2022-07-17 00:52:16'),
        (4, NULL, 4, 4, NULL, NULL, NULL, NULL, 1, 3, 20, 155, '4', 'Mr.CF', 'কাপাসিয়া', 'KAPASIA', 'কাপাসিয়া', 'KAPASIA', 'কাপাসিয়া', 'KAPASIA', '01*********', 'dcf@bforest.gov.bd', 'dcf@bforest.gov.bd', '2022-06-21 11:25:36', NULL, 'WZVLAg7KkY', 1, NULL, 1, '2022-06-21 11:25:36', '2022-07-17 00:52:41'),
        (5, NULL, 5, 5, NULL, NULL, NULL, NULL, 1, 2, 10, 72, '5', 'Mr.DCF', 'ফটিকছড়ি', 'FATIKCHHARI', 'ফটিকছড়ি', 'FATIKCHHARI', 'ফটিকছড়ি', 'FATIKCHHARI', '01*********', 'cf@bforest.gov.bd', 'cf@bforest.gov.bd', '2022-06-21 11:25:36', NULL, 'KiWu5aLToo', 1, NULL, 1, '2022-06-21 11:25:36', '2022-07-17 00:53:12'),
        (6, NULL, 6, 6, NULL, NULL, NULL, NULL, 1, 2, 10, 69, '6', 'Mr.ACCF', 'বোয়ালখালী', 'BOALKHALI', 'বোয়ালখালী', 'BOALKHALI', 'বোয়ালখালী', 'BOALKHALI', '01*********', 'accf@bforest.gov.bd', 'accf@bforest.gov.bd', '2022-06-21 11:25:36', NULL, 'bFTJEwqk3D', 1, NULL, 1, '2022-06-21 11:25:37', '2022-07-17 00:53:35'),
        (7, NULL, 7, 7, NULL, NULL, NULL, NULL, 8, 6, NULL, NULL, '7', 'Mr.DFO', 'রংপুর ডিএফও', 'Rangpur DFO', 'রংপুর ডিএফও', 'Rangpur DFO', 'রংপুর', 'Rangpur', '01*********', 'dfo@bforest.gov.bd', 'dfo@bforest.gov.bd', '2022-06-21 11:25:37', NULL, '5hT6O9MX9PPSUQOUWEWRpBh44szqq7H5qVkNQr17a3AT2zh8plOse4RphTea', 1, NULL, 1, '2022-06-21 11:25:37', '2022-08-16 10:45:55'),
        (8, NULL, 8, 8, NULL, NULL, NULL, NULL, 1, 3, NULL, NULL, '8', 'Mr.ACF', 'ঢাকা এসিএফ', 'Dhaka ACF', NULL, NULL, 'ঢাকা', 'Dhaka', '01*********', 'acf@bforest.gov.bd', 'dhaka_acf@bforest.gov.bd', '2022-06-21 11:25:37', NULL, '2rGiRTy7Mu8cEkgalzJM7xWVAvRheMZS1LG7cDLBiAbOxQOVtThjUaoWfOhW', 1, NULL, 1, '2022-06-21 11:25:37', '2022-08-12 20:04:53'),
        (9, NULL, 9, 9, NULL, NULL, NULL, NULL, 1, 3, 20, NULL, '9', NULL, 'শ্রীপুর', 'SREEPUR', NULL, NULL, NULL, NULL, '01*********', 'sreepur@bforest.gov.bd', 'sreepur@bforest.gov.bd', '2022-08-11 02:37:32', NULL, 'r6q4maAQsof2486CaZ5ihjMSZDtZGqnz1W4Aup22Px6dx0wP86JCwp9HWvJg', 1, 1, NULL, '2022-08-11 02:37:32', '2022-08-11 02:37:32'),
        (10, NULL, 10, 10, NULL, NULL, NULL, NULL, 1, 3, 20, 152, '10', NULL, 'গাজীপুর সদর', 'GAZIPUR SADAR', NULL, NULL, NULL, NULL, '01*********', 'gazipur-sadar@bforest.gov.bd', 'gazipur-sadar@bforest.gov.bd', '2022-08-11 02:37:57', NULL, 'EkSAflMbEgSdMwhy4JqBrkfF3OjeL9K5PmMstt0Y1xHMH07CqmV9Ou5HHAqw', 1, 1, NULL, '2022-08-11 02:37:57', '2022-08-11 02:37:57'),
        (11, NULL, 9, 9, NULL, NULL, NULL, NULL, 8, 6, 54, NULL, '11', NULL, 'সুন্দরগঞ্জ এসএফপিসি', 'SUNDARGANJ SFPC', NULL, NULL, NULL, NULL, '01*********', 'sundarganj-sfpc@bforest.gov.bd', 'sundarganj-sfpc@bforest.gov.bd', '2022-08-12 19:52:28', NULL, 'S33gr0q8TT2Zsesm5c3kZVCp57K2uyNQvH85L5ZrZV8j0mkJ7uNX4kX24Uv4', 1, 1, 1, '2022-08-12 19:52:28', '2022-08-12 19:53:31'),
        (12, NULL, 10, 10, NULL, NULL, NULL, NULL, 8, 6, 54, 145, '12', NULL, 'ফুলছড়ি এসএফপিসি', 'FULCHHARI SFPC', NULL, NULL, NULL, NULL, '01*********', 'fulchhari-sfpc@bforest.gov.bd', 'fulchhari-sfpc@bforest.gov.bd', '2022-08-12 19:54:37', NULL, 'jc7RrSk69CXfLNWVf9kNbEvac7NlJ5ETgNVC9FUrdwBTecbj5IqvO5uGXpSM', 1, 1, NULL, '2022-08-12 19:54:37', '2022-08-12 19:54:37'),
        (13, NULL, 8, 8, NULL, NULL, NULL, NULL, 8, 6, NULL, NULL, '13', NULL, 'রংপুর আসিফ', 'Rangpur ACF', NULL, NULL, 'রংপুর', 'Rangpur', '01712616057', 'rangpur_acf@bforest.gov.bd', 'rangpur_acf@bforest.gov.bd', '2022-08-12 20:01:33', NULL, 'xxzfAx8TCG5hUa9yyciuuw6VmIE6cLIaiYFZxDhIYnoOBC4e6bm5PDabsDve', 1, 1, NULL, '2022-08-12 20:01:33', '2022-08-16 10:15:43'),
        (14, NULL, 9, 9, NULL, NULL, NULL, NULL, 8, 6, 56, NULL, '14', NULL, 'লালমনিরহাট এসএফএনটিসি ইউসার', 'Lalmonirhat SFNTC User', 'লালমনিরহাট', 'Lalmonirhat', 'লালমনিরহাট', 'Lalmonirhat', NULL, 'lalsfntc@bforest.gov.bd', 'lalsfntc@bforest.gov.bd', '2022-08-16 10:19:10', NULL, 'IPY3pGwzoMUOrtH6BDi7HzotTBGVui89oAtg5gJeFWzLpQFVhVdzr6nexmTz', 1, 1, NULL, '2022-08-16 10:19:10', '2022-08-16 10:19:10'),
        (15, NULL, 10, 10, NULL, NULL, NULL, NULL, 8, 6, 56, 255, '15', NULL, 'পাটগ্রাম এসএফপিসি', 'PATGRAM SFPC', NULL, NULL, NULL, NULL, '01*********', 'patgram-sfpc@bforest.gov.bd', 'patgram-sfpc@bforest.gov.bd', '2022-08-16 10:19:58', NULL, '2U8FPJ5uJ9DXCbKcGJ2iXMzTYGJPU7i5bE12MbqJHzLxKD9lv2XGfQRMelVU', 1, 1, NULL, '2022-08-16 10:19:59', '2022-08-16 10:19:59')");
        Schema::enableForeignKeyConstraints();

    }
}