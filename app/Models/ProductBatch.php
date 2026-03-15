<?php
// app/Models/ProductBatch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductBatch extends Model
{
    protected $fillable = [
        'product_id',
        'batch_number',
        'purchase_price',
        'initial_quantity',
        'remaining_quantity',
        'manufacturing_date',
        'expiry_date',
        'received_date',
        'supplier',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'received_date' => 'date',
        'purchase_price' => 'decimal:2'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // Vérifier si le lot est expiré
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    // Vérifier si le lot est valide (non expiré et quantité > 0)
    public function isValid(): bool
    {
        return !$this->isExpired() && $this->remaining_quantity > 0 && $this->is_active;
    }

    // Calculer la valeur du lot restant
    public function getRemainingValue(): float
    {
        return $this->remaining_quantity * $this->purchase_price;
    }

    // Générer un numéro de lot unique
    public static function generateBatchNumber($productId)
    {
        $date = now()->format('Ymd');
        $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        return "BATCH-{$date}-{$productId}-{$random}";
    }
}