<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'invoice_number',
        'amount',
        'tax_amount',
        'total_amount',
        'currency',
        'status',
        'issued_at',
        'due_at',
        'paid_at',
        'items'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
        'paid_at' => 'datetime',
        'items' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function generateInvoiceNumber()
    {
        $year = now()->year;
        $lastInvoice = self::whereYear('created_at', $year)->latest()->first();
        $number = $lastInvoice ? (int)substr($lastInvoice->invoice_number, -6) + 1 : 1;
        
        return 'KA' . $year . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);
    }
}