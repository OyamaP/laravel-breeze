<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Image;

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
    public function definition()
    {
        return [
            'name' => fake()->text(10),
            'information' => fake()->realText(100),
            'price' => fake()->numberBetween(10, 100000),
            'is_selling' => fake()->numberBetween(0, 1),
            'sort_order' => fake()->randomNumber,
            'shop_id' => fake()->numberBetween(1, 4),
            'secondary_category_id' => fake()->numberBetween(1, 8),
            'image1' => Image::factory(),
            'image2' => Image::factory(),
            'image3' => Image::factory(),
            'image4' => Image::factory(),
        ];
    }
}
