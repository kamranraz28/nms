<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $gender = $faker->randomElement(['male', 'female']);
        // return [
        //     'name' => $faker->name($gender),
        //     'email' => $faker->safeEmail,
        //     'username' => $faker->userName,
        //     'phone' => $faker->phoneNumber,
        //     'gender' => $gender,
        //     'address' => $faker->address,
        //     'dob' => $faker->date($format = 'Y-m-d', $max = 'now'),
        //     'password' => bcrypt('secret')
        // ];


        return [
            'name' => $this->faker->name(),
            //'title_en' => $this->faker->name(),
            //'title_bn' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
            'default' => true,
            'walking' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
