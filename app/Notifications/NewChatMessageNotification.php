<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewChatMessage extends Notification
{
    use Queueable;

  public $message;
    public $sender;

    public function __construct($message, $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
    }

    public function via($notifiable)
    {
        return ['database']; // You can add 'mail', 'broadcast', etc.
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'sender' => $this->sender->name,
            'sender_id' => $this->sender->id,
        ];
    }
}