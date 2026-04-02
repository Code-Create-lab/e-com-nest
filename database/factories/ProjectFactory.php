<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(ProjectStatus::cases());
        $progress = match ($status) {
            ProjectStatus::Planning => fake()->numberBetween(0, 25),
            ProjectStatus::Active => fake()->numberBetween(20, 85),
            ProjectStatus::OnHold => fake()->numberBetween(15, 75),
            ProjectStatus::Completed => 100,
        };

        $startDate = fake()->dateTimeBetween('-3 months', '+2 weeks');
        $endDate = fake()->boolean(80)
            ? fake()->dateTimeBetween($startDate, '+6 months')
            : null;

        return [
            'customer_id' => Customer::factory(),
            'project_name' => fake()->bs(),
            'description' => fake()->paragraph(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'progress' => $progress,
        ];
    }
}
