<?php

namespace App\Notifications;

use App\Models\ProcurementRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewProcurementRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $request;

    public function __construct(ProcurementRequest $request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Optional: or only ['database']
    }

    
    public function toDatabase($notifiable)
    {
        return [
            'request_id' => $this->request->id,
            'message' => 'New procurement request for raw material: ' . $this->request->rawMaterial->name
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Procurement Request')
            ->line('You have a new procurement request.')
            ->action('View Request', url(route('procurement.requests.show', $this->request->id)))
            ->line('Please log in to accept it.');
    }
}
