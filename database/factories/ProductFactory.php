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
        return [
            'wc_id' => fake()->unique()->randomNumber(),
            'type' => fake()->randomElement(['simple', 'variable']),
            'sku' => fake()->unique()->numerify('SKU-####'),
            'name' => fake()->words(3, true),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'published' => fake()->boolean(80),
            'featured' => fake()->boolean(20),
            'visibility' => fake()->randomElement(['visible', 'catalog', 'search', 'hidden']),
            'price' => fake()->randomFloat(2, 10, 1000),
            'in_stock' => fake()->boolean(90),
            'stock' => fake()->numberBetween(0, 100),
            'weight' => fake()->randomFloat(2, 0.1, 50),
            'shipping_class' => fake()->word(),
            'url' => fake()->url(),
            'attributes' => [],
        ];
    }
}
