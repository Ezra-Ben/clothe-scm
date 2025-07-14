<?php

namespace App\Notifications;

use App\Models\ProcurementReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryRejectedNotification extends Notification implements ShouldQueue
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
            ->subject('Delivery Rejected - Action Required')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Unfortunately, your recent delivery has been rejected by our admin team.')
            ->line('**Material:** ' . $this->reply->request->rawMaterial->name)
            ->line('**Quantity:** ' . $this->reply->quantity_confirmed . ' units')
            ->line('**Rejection Reason:** ' . $this->reply->rejection_reason)
            ->action('View Details', url(route('procurement.requests.show', $this->reply->request->id)))
            ->line('Please contact us to resolve this issue or arrange a replacement delivery.')
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
            'message' => 'Your delivery for ' . $this->reply->request->rawMaterial->name . ' has been rejected',
            'material_name' => $this->reply->request->rawMaterial->name,
            'quantity' => $this->reply->quantity_confirmed,
            'rejection_reason' => $this->reply->rejection_reason,
            'reply_id' => $this->reply->id,
            'request_id' => $this->reply->request->id,
        ];
    }
}