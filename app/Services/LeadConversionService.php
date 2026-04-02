<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class LeadConversionService
{
    public function convert(Lead $lead): Customer
    {
        return DB::transaction(function () use ($lead): Customer {
            if ($lead->customer) {
                $lead->update([
                    'status' => LeadStatus::Converted,
                    'converted_at' => $lead->converted_at ?? now(),
                ]);

                return $lead->customer;
            }

            $customer = Customer::create([
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'company_name' => null,
                'address' => null,
            ]);

            $lead->update([
                'customer_id' => $customer->id,
                'status' => LeadStatus::Converted,
                'converted_at' => now(),
            ]);

            return $customer;
        });
    }
}
