<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\LeadStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Database\Seeder;

class AdminPanelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::factory(12)->create();

        $invoiceSequence = 1;

        $customers->each(function (Customer $customer) use (&$invoiceSequence): void {
            $projects = Project::factory(fake()->numberBetween(1, 3))
                ->for($customer)
                ->create();

            $projects->each(function (Project $project) use ($customer, &$invoiceSequence): void {
                if (! fake()->boolean(75)) {
                    return;
                }

                $items = collect(range(1, fake()->numberBetween(1, 4)))
                    ->map(function (): array {
                        return InvoiceItem::factory()->make()->toArray();
                    });

                $subtotal = (float) $items->sum('total');
                $discount = fake()->randomFloat(2, 0, min(500, $subtotal));
                $status = fake()->randomElement([InvoiceStatus::Paid, InvoiceStatus::Unpaid]);

                $invoice = Invoice::factory()
                    ->for($customer)
                    ->for($project)
                    ->state([
                        'invoice_number' => 'INV-'.now()->format('Ym').'-'.str_pad((string) $invoiceSequence++, 4, '0', STR_PAD_LEFT),
                        'subtotal_amount' => $subtotal,
                        'discount' => $discount,
                        'final_amount' => max($subtotal - $discount, 0),
                        'status' => $status,
                    ])
                    ->create();

                $invoice->items()->createMany($items->all());
            });
        });

        Lead::factory(10)->create();

        $customers->take(3)->each(function (Customer $customer): void {
            Lead::factory()
                ->converted()
                ->for($customer)
                ->state([
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'source' => fake()->randomElement(['Referral', 'Trade Show', 'Website']),
                    'status' => LeadStatus::Converted,
                ])
                ->create();
        });
    }
}
