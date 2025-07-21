<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductionOrderCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $productionOrder;

    public function __construct($productionOrder)
    {
        $this->productionOrder = $productionOrder;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new production order has been created for ' . $this->productionOrder->product->name,
            'url'     => route('production_orders.show', $this->productionOrder->id),
        ];
    }
}
