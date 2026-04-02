<?php

namespace Database\Factories;

use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $price = fake()->randomFloat(2, 150, 2500);

        return [
            'name' => fake()->randomElement([
                'Discovery Workshop',
                'Design Sprint',
                'Development Retainer',
                'QA Cycle',
                'Infrastructure Setup',
            ]),
            'quantity' => $quantity,
            'price' => $price,
            'total' => $quantity * $price,
        ];
    }
}
