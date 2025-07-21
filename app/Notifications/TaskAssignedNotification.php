<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // You can also add 'broadcast' or 'slack'
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Task Assignment')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("You have been assigned a new task: **{$this->task->name}**.")
            ->action('View Task', route('employee.task.show', $this->task->id))
            ->line('Thank you for your commitment!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'name' => $this->task->name,
            'message' => 'You have been assigned a new task.',
            'url' => route('employee.task.show', $this->task->id),
        ];
    }
}
