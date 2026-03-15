<?php
// app/Models/Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'type',
        'movement_type',
        'product_id',
        'quantity',
        'unit_price',
        'total_amount',
        'reason',
        'customer_supplier',
        'payment_method',
        'payment_status',
        'invoice_date',
        'stock_movement_id'  // Ajoutez ceci
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
    
    public function stockMovement(): BelongsTo
    {
        return $this->belongsTo(StockMovement::class);
    }

    public static function generateInvoiceNumber($type)
    {
        $prefix = $type === 'purchase' ? 'ACH' : 'VEN';
        $year = date('Y');
        $month = date('m');
        
        $lastInvoice = self::where('invoice_number', 'like', "{$prefix}-{$year}{$month}-%")
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "{$prefix}-{$year}{$month}-{$newNumber}";
    }
}