<?php

namespace App\Services;

use App\Models\Invoice;

class InvoiceNumberGenerator
{
    public function generate(): string
    {
        $prefix = 'INV-'.now()->format('Ym').'-';

        $latestInvoice = Invoice::query()
            ->where('invoice_number', 'like', "{$prefix}%")
            ->latest('id')
            ->value('invoice_number');

        $nextNumber = 1;

        if ($latestInvoice) {
            $lastSequence = (int) substr($latestInvoice, strrpos($latestInvoice, '-') + 1);
            $nextNumber = $lastSequence + 1;
        }

        return $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
