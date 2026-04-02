<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 1500, 18000);
        $discount = fake()->randomFloat(2, 0, 500);

        return [
            'customer_id' => Customer::factory(),
            'project_id' => null,
            'invoice_number' => 'INV-'.fake()->unique()->numerify('######'),
            'issue_date' => fake()->dateTimeBetween('-2 months', 'now'),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
            'subtotal_amount' => $subtotal,
            'discount' => $discount,
            'final_amount' => max($subtotal - $discount, 0),
            'status' => fake()->randomElement(InvoiceStatus::cases()),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
