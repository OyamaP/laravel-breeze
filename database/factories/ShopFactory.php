<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'owner_id' => fake()->numberBetween(1, 6),
            'name' => fake()->text(10),
            'information' => fake()->realText(100),
            'filename' => '',
            'is_selling' => true,
        ];
    }
}
