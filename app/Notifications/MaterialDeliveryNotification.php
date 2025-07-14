<?php

namespace App\Notifications;

use App\Models\ProcurementReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaterialDeliveryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reply;

    /**
     * Create a new notification instance.
     */
    public function __construct(ProcurementReply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Material Delivery Incoming - Validation Required')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A supplier has marked materials as shipped and they are incoming for delivery.')
            ->line('**Material:** ' . $this->reply->request->rawMaterial->name)
            ->line('**Supplier:** ' . $this->reply->supplier->vendor->name)
            ->line('**Quantity:** ' . $this->reply->quantity_confirmed . ' units')
            ->line('**Expected Delivery:** ' . $this->reply->expected_delivery_date)
            ->action('Validate Delivery', url(route('procurement.replies.indexForRequest', $this->reply->request->id)))
            ->line('Please validate the delivery once materials arrive to update inventory.')
            ->salutation('Regards, ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Material delivery incoming from ' . $this->reply->supplier->vendor->name,
            'material_name' => $this->reply->request->rawMaterial->name,
            'supplier_name' => $this->reply->supplier->vendor->name,
            'quantity' => $this->reply->quantity_confirmed,
            'expected_date' => $this->reply->expected_delivery_date,
            'reply_id' => $this->reply->id,
            'request_id' => $this->reply->request->id,
        ];
    }
}
