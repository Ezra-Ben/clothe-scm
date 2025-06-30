<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProcurementRequestApproved extends Notification
{
    use Queueable;

   
    public function via( $notifiable)
    {
        return ['mail', 'database'];
    }

    
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
           ->subject('Procurement Request Approved')
            ->line('Your procurement request has been approved.');
    }

    
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your procurement request has been approved.'
        ];
    }
}
