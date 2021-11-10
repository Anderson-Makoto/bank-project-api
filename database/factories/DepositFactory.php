<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepositFactory extends Factory
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
            "value" => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 500, $max = 2000),
            "updated_at" => $this->faker->dateTimeBetween("-60 days", "now"),
            "created_at" => $this->faker->dateTimeBetween("-60 days", "now"),
            "check_img" => $this->faker->image("public/img/checks", 20, 20),
            "fk_deposit_status" => 1,
            "fk_user" => 2
        ];
    }
}
