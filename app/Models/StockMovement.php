<?php
// app/Models/StockMovement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';
    const TYPE_ADJUSTMENT = 'adjustment';

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'unit_price',
        'total_price',
        'reason',
        'note',
        'invoice_id'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relation avec le produit
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relation avec le lot
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }

    /**
     * RELATION AVEC LA FACTURE - AJOUTÉE ICI
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Vérifier si c'est un achat
     */
    public function isPurchase(): bool
    {
        return $this->type === 'in' && $this->reference_type === 'purchase';
    }

    /**
     * Vérifier si c'est une vente
     */
    public function isSale(): bool
    {
        return $this->type === 'out' && $this->reference_type === 'sale';
    }
}