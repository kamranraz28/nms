<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    protected $model = Admin::class;
    public function definition()
    {
        return [
            //'name' => $this->faker->name(),
            //'email' => $this->faker->unique()->safeEmail(),
            'user_type_id' => 1,
            'role_id' => 1,
            'division_id' => 3,
            'district_id' => 18,
            'upazila_id' => 117,
            'name' => 'Mr. Admin',
            'title_en' => 'Mr. Admin',
            'title_bn' => 'মিস্টার এডমিন',
            'office_en' => 'Master Admin Office, West Agargaon, Dhaka',
            'office_bn' => 'মাস্টার অ্যাডমিন অফিস, পশ্চিম আগারগাঁও, ঢাকা',
            'contact' => '01700000000',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
        ];
    }
}
