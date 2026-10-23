<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     */
    public function creating(Order $order): void
    {
        if (empty($order->invoice_number)) {
            $order->invoice_number = $this->generateInvoiceNumber();
        }
    }

    /**
     * Generate a unique invoice number.
     */
    protected function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(8));
        
        return "INV-{$date}-{$random}";
    }
}
