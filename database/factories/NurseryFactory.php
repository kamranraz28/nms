<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Nursery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class NurseryFactory extends Factory
{
    protected $model = Nursery::class;
    public function definition()
    {
        $admin = Admin::office(Admin::BO)->first();

        return [
            //'name' => $this->faker->name(),
            'admin_id' => 9,
            'code' => 1,
            'division_id' => $admin->division_id,
            'district_id' => $admin->district_id,
            'upazila_id' => $admin->upazila_id,
            'email' => $this->faker->unique()->safeEmail(),
            'title_en' => 'Mohammad Rokibullah',
            'title_bn' => 'মোহাম্মদ রকিবুল্লাহ',
            'office_en' => 'Nursery - 1, West Agargaon, Dhaka',
            'office_bn' => 'নার্সারী- ১, পশ্চিম আগারগাঁও, ঢাকা',
            'details_en' => 'Nursery - 1, West Agargaon, Dhaka',
            'details_bn' => 'নার্সারী- ১, পশ্চিম আগারগাঁও, ঢাকা',
            'latitude' => '23.7823194',
            'longitude' => '90.3694966',
            'contact' => '01700000000',
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
        ];
    }
}
