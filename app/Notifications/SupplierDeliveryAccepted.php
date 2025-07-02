<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SupplierDeliveryAccepted extends Notification
{
    use Queueable;
    public $procurementRequest;

    public function __construct($procurementRequest)
    {
        $this->procurementRequest = $procurementRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Supplier has accepted delivery for procurement request #{$this->procurementRequest->id}.",
            'procurement_request_id' => $this->procurementRequest->id,
            'actions' => [
                'confirm_url' => route('admin.procurement.confirmDelivery', $this->procurementRequest->id),
            ]
        ];
    }
}