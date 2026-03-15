<?php
// app/Notifications/StockMovementNotification.php

namespace App\Notifications;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class StockMovementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $stockMovement;
    protected $product;

    public function __construct(StockMovement $stockMovement, Product $product)
    {
        $this->stockMovement = $stockMovement;
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $type = $this->stockMovement->type === 'in' ? 'Entrée' : 'Sortie';
        $icon = $this->stockMovement->type === 'in' ? 'arrow-down' : 'arrow-up';
        $color = $this->stockMovement->type === 'in' ? 'success' : 'danger';
        
        return [
            'type' => 'stock_movement',
            'movement_id' => $this->stockMovement->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'movement_type' => $this->stockMovement->type,
            'quantity' => $this->stockMovement->quantity,
            'unit_price' => $this->stockMovement->unit_price,
            'total_price' => $this->stockMovement->total_price,
            'reason' => $this->stockMovement->reason,
            'message' => $type . ' de ' . $this->stockMovement->quantity . ' ' . $this->product->name,
            'action_url' => '/products/' . $this->product->id,
            'icon' => $icon,
            'color' => $color,
            'time' => now()->format('d/m/Y H:i')
        ];
    }
}