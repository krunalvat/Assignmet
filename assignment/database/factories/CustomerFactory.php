<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => 1,
            'first_name' => $this->faker->firstNameMale(),
            'last_name' => $this->faker->lastName(),
            'avatar' => '',
            'city' => $this->faker->city(),
            'birthdate' => $this->faker->date('Y-m-d'),
        ];
    }
}
