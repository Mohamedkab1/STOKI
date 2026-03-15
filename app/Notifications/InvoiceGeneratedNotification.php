<?php
// app/Notifications/InvoiceGeneratedNotification.php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class InvoiceGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $type = $this->invoice->type === 'purchase' ? 'Achat' : 'Vente';
        
        return [
            'type' => 'invoice_generated',
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'product_name' => $this->invoice->product->name,
            'quantity' => $this->invoice->quantity,
            'unit_price' => $this->invoice->unit_price,
            'total_amount' => $this->invoice->total_amount,
            'customer_supplier' => $this->invoice->customer_supplier,
            'message' => 'Facture ' . $this->invoice->invoice_number . ' générée - ' . $type . ' de ' . $this->invoice->quantity . ' ' . $this->invoice->product->name,
            'action_url' => '/invoices/' . $this->invoice->id,
            'icon' => 'file-invoice',
            'color' => 'primary',
            'time' => now()->format('d/m/Y H:i')
        ];
    }
}