<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "description" => $this->faker->text(15),
            "value" => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 250, $max = 500),
            "purchase_date" => $this->faker->dateTimeBetween("-60 days", "now"),
            "fk_user" => 2
        ];
    }
}
