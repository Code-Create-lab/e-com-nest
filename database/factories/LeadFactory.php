<?php

namespace Database\Factories;

use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'source' => fake()->randomElement(['Website', 'Referral', 'LinkedIn', 'Email Campaign', 'Google Ads']),
            'status' => fake()->randomElement([
                LeadStatus::New,
                LeadStatus::InProgress,
                LeadStatus::Rejected,
            ]),
            'notes' => fake()->sentence(),
        ];
    }

    public function converted(): static
    {
        return $this->state(fn () => [
            'status' => LeadStatus::Converted,
            'converted_at' => now(),
        ]);
    }
}
