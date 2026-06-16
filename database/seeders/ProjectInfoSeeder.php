<?php

namespace Database\Seeders;

use App\Enums\EngagementType;
use App\Enums\InvoiceStatus;
use App\Enums\ProjectStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Project;
use App\Services\InvoiceNumberGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectInfoSeeder extends Seeder
{
    /**
     * Seed projects from projectinfo.txt and create one invoice per project
     * for the project total amount.
     */
    public function run(): void
    {
        $generator = new InvoiceNumberGenerator();

        // [name, total, paid, status, date]
        $projects = [
            ['Protect line', 5000, 5000, ProjectStatus::Completed, null],
            ['JMD', 15000, 15000, ProjectStatus::Completed, null],
            ['Youngistaan', 22000, 22000, ProjectStatus::Completed, null],
            ['JSCK', 6000, 6000, ProjectStatus::Completed, null],
            ['Growess', 35000, 11000, ProjectStatus::Active, null],
            ['KushwahaShadi', 35000, 5000, ProjectStatus::OnHold, null],
            ['JMD Store', 19500, 19500, ProjectStatus::Active, '2024-07-16'],
            ['GlobeGateMigration', 6500, 6500, ProjectStatus::Active, '2024-10-15'],
            ['JMD Re-Build', 8500, 0, ProjectStatus::OnHold, '2024-12-01'],
            ['Growess (2)', 35000, 8000, ProjectStatus::Active, '2025-05-10'],
            ['Trust Pay (Web)', 70000, 10000, ProjectStatus::Active, '2025-08-03'],
        ];

        DB::transaction(function () use ($projects, $generator): void {
            $customer = Customer::firstOrCreate(
                ['name' => 'Sandeep Chandigarh'],
                ['company_name' => 'Sandeep Chandigarh'],
            );

            foreach ($projects as [$name, $total, $paid, $status, $date]) {
                $project = Project::create([
                    'customer_id' => $customer->id,
                    'project_name' => $name,
                    'status' => $status,
                    'progress' => $status === ProjectStatus::Completed ? 100 : 0,
                    'engagement_type' => EngagementType::OneTime,
                    'total_development_cost' => $total,
                    'start_date' => $date,
                ]);

                $invoice = Invoice::create([
                    'customer_id' => $customer->id,
                    'project_id' => $project->id,
                    'invoice_number' => $generator->generate(),
                    'issue_date' => $date ?? now()->toDateString(),
                    'due_date' => null,
                    'subtotal_amount' => $total,
                    'discount' => 0,
                    'final_amount' => $total,
                    'status' => $paid >= $total ? InvoiceStatus::Paid : InvoiceStatus::Unpaid,
                    'notes' => "Paid: {$paid} / Total: {$total}",
                ]);

                $invoice->items()->create([
                    'name' => $name,
                    'quantity' => 1,
                    'price' => $total,
                    'total' => $total,
                ]);
            }
        });
    }
}
