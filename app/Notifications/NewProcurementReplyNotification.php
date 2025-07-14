<?php

namespace App\Notifications;

use App\Models\ProcurementReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewProcurementReplyNotification extends Notification implements ShouldQueue
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
            ->subject('New Procurement Reply Received')
            ->greeting('Hello!')
            ->line('A supplier has submitted a reply to procurement request #' . $this->reply->procurement_request_id)
            ->line('Supplier: ' . ($this->reply->supplier->vendor->user->name ?? 'Unknown'))
            ->line('Quantity Confirmed: ' . $this->reply->quantity_confirmed)
            ->line('Expected Delivery: ' . $this->reply->expected_delivery_date)
            ->line('Status: ' . ucfirst($this->reply->status))
            ->action('Review Reply', url(route('procurement.replies.show', $this->reply->id)))
            ->line('Please review and take appropriate action.')
            ->salutation('Regards, ' . config('app.name'));
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'New reply received for procurement request #' . $this->reply->procurement_request_id,
            'reply_id' => $this->reply->id,
            'request_id' => $this->reply->procurement_request_id,
            'supplier_name' => $this->reply->supplier->vendor->user->name ?? 'Unknown',
            'quantity_confirmed' => $this->reply->quantity_confirmed,
            'status' => $this->reply->status,
        ];
    }
}
