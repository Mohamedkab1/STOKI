<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use \App\Traits\BelongsToUser;

    protected $fillable = [
        'name', 
        'sku', 
        'description', 
        'image',
        'price', 
        'purchase_price', 
        'selling_price', 
        'quantity', 
        'min_stock', 
        'category_id',
        'user_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity' => 'integer',
        'min_stock' => 'integer'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_stock;
    }

    public function updateStock($quantity, $type)
    {
        if ($type === 'in') {
            $this->increment('quantity', $quantity);
        } else {
            $this->decrement('quantity', $quantity);
        }
    }

        // Accesseur pour l'URL de l'image
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    // Accesseur pour l'image par défaut
    public function getDefaultImageAttribute()
    {
        return 'https://via.placeholder.com/300x200?text=' . urlencode($this->name);
    }

    public function getCurrentStock()
    {
        // Recalculer le stock réel à partir des mouvements
        $entries = StockMovement::where('product_id', $this->id)->where('type', 'in')->sum('quantity');
        $exits = StockMovement::where('product_id', $this->id)->where('type', 'out')->sum('quantity');
        $calculatedStock = $entries - $exits;
        
        // Si différent, corriger
        if ($this->quantity != $calculatedStock) {
            \Log::warning("Stock incohérent pour produit {$this->id}", [
                'stock_actuel' => $this->quantity,
                'stock_calcule' => $calculatedStock
            ]);
            $this->update(['quantity' => $calculatedStock]);
        }
        
        return $this->quantity;
    }
}