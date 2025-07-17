<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\InboundShipment;

class InboundShipmentCreatedNotification extends Notification
{
    use Queueable;

    protected $shipment;

    public function __construct(InboundShipment $shipment)
    {
        $this->shipment = $shipment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'shipment_id' => $this->shipment->id,
            'message' => 'A new inbound shipment has been created.',
            'url' => route('logistics.orders.inbound.show', $this->shipment->id),
        ];
    }
}