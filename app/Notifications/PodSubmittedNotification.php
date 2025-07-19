<?php
namespace App\Notifications;

use App\Models\Pod;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PodSubmittedNotification extends Notification
{
    use Queueable;

    public Pod $pod;

    public function __construct(Pod $pod)
    {
        $this->pod = $pod;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A new Proof of Delivery has been submitted (Shipment #' . $this->pod->shipment_id . ').',
            'pod_id' => $this->pod->id,
            'url' => route('pods.show', $this->pod->id),
        ];
    }
}