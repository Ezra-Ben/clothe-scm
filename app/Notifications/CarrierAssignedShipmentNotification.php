<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Carrier;
use App\Models\OutboundShipment;
use App\Models\InboundShipment;

class CarrierAssignedShipmentNotification extends Notification
{
    use Queueable;

    protected $shipment;
    protected $type;

    public function __construct($shipment, $type = 'outbound')
    {
        $this->shipment = $shipment;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'shipment_id' => $this->shipment->id,
            'type' => $this->type,
            'message' => 'You have been assigned a new ' . $this->type . ' shipment.',
            'url' => $this->type === 'outbound'
                ? route('outbound.show', $this->shipment->order_id)
                : route('inbound.show', $this->shipment->id),
        ];
    }
}