<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Subscription;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function createInvoice(User $user, Subscription $subscription)
    {
        $invoice = new Invoice();
        $invoice->user_id = $user->id;
        $invoice->subscription_id = $subscription->id;
        $invoice->invoice_number = $invoice->generateInvoiceNumber();
        $invoice->amount = $subscription->membershipPlan->price;
        $invoice->tax_amount = $this->calculateTax($subscription->membershipPlan->price);
        $invoice->total_amount = $invoice->amount + $invoice->tax_amount;
        $invoice->currency = 'TRY';
        $invoice->status = 'pending';
        $invoice->issued_at = now();
        $invoice->due_at = now()->addDays(30);
        $invoice->items = [
            [
                'name' => $subscription->membershipPlan->name . ' Üyelik',
                'quantity' => 1,
                'unit_price' => $subscription->membershipPlan->price,
                'total' => $subscription->membershipPlan->price
            ]
        ];
        
        $invoice->save();
        
        return $invoice;
    }

    public function generatePDF(Invoice $invoice)
    {
        $data = [
            'invoice' => $invoice->load('user', 'subscription.membershipPlan'),
            'company' => [
                'name' => 'KadınAtlası.com',
                'address' => 'Türkiye',
                'tax_number' => '1234567890',
                'phone' => '+90 555 123 45 67',
                'email' => 'info@kadinatlasi.com'
            ]
        ];

        $pdf = Pdf::loadView('invoices.pdf', $data);
        return $pdf->output();
    }

    private function calculateTax($amount)
    {
        return $amount * 0.18; // %18 KDV
    }
}