<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\OutboundShipment;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OutboundShipmentCreatedNotification extends Notification
{
    use Queueable;

    protected $shipment;

    public function __construct(OutboundShipment $shipment)
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
            'order_id' => $this->shipment->order_id,
            'message' => 'A new outbound shipment has been created.',
            'url' => url(route('outbound.show', $this->shipment->id)),
        ];
    }
}