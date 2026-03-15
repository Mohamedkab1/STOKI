<?php
// app/Notifications/LowStockNotification.php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['database']; // Uniquement en base de données, pas de mail
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'low_stock',
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_stock' => $this->product->quantity,
            'min_stock' => $this->product->min_stock,
            'sku' => $this->product->sku,
            'price' => $this->product->price,
            'message' => '⚠️ Stock faible: ' . $this->product->name . ' (' . $this->product->quantity . ' unités restantes)',
            'action_url' => '/products/' . $this->product->id,
            'icon' => 'exclamation-triangle',
            'color' => 'warning',
            'time' => now()->format('d/m/Y H:i'),
            'details' => [
                'stock_actuel' => $this->product->quantity,
                'stock_minimum' => $this->product->min_stock,
                'manque' => $this->product->min_stock - $this->product->quantity
            ]
        ];
    }
}