<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Unit;
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
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'stock_quantity' => $this->faker->numberBetween(1, 100),
            'image' => $this->faker->imageUrl(),
            'code' => $this->faker->unique()->randomNumber(8),
            'cost_price' => $this->faker->randomFloat(2, 1, 100),
            'category_id' => Category::factory()->create()->first()->id,
            'unit_id' => Unit::factory()->create()->first()->id,
            'barcode' => $this->faker->ean13,

            //
        ];
    }
}
