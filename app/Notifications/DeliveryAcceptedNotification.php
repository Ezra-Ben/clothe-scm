<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ProcurementReply;

class DeliveryAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reply;

    public function __construct(ProcurementReply $reply)
    {
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Delivery Accepted - Reply #' . $this->reply->id)
            ->greeting('Great news!')
            ->line('Your delivery for procurement request #' . $this->reply->procurement_request_id . ' has been accepted by the admin after inspection.')
            ->line('**Reply Details:**')
            ->line('- Reply ID: ' . $this->reply->id)
            ->line('- Confirmed Quantity: ' . $this->reply->quantity_confirmed)
            ->line('- Status: Delivered & Accepted')
            ->line('The materials have been added to inventory and production planning has been updated.')
            ->action('View Reply Details', url(route('procurement.replies.show', $this->reply->id)))
            ->line('Thank you for your quality delivery!')
            ->salutation('Regards, ' . config('app.name'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'delivery_accepted',
            'reply_id' => $this->reply->id,
            'procurement_request_id' => $this->reply->procurement_request_id,
            'quantity_confirmed' => $this->reply->quantity_confirmed,
            'message' => 'Your delivery for procurement request #' . $this->reply->procurement_request_id . ' has been accepted after inspection.',
        ];
    }
}
