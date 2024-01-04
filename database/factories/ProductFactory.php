<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actual_price = rand(1000, 10000);
        return [
            "name" => fake()->name(),
            "actual_price" => $actual_price,
            "primary_unit_id" => rand(1, 8),
            "primary_price" => $actual_price + 300,
            "stock" => 100,
            "user_id" => 1
        ];
    }
}
