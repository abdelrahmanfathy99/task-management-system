<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverdueNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Task $task
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $dueDate = $this->task->due_date?->toDateString() ?? 'N/A';

        return (new MailMessage)
            ->subject('Task overdue: '.$this->task->title)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('The following task is overdue.')
            ->line('Title: '.$this->task->title)
            ->line('Due date: '.$dueDate)
            ->line('Status: '.$this->task->status->value)
            ->line('Priority: '.$this->task->priority->value);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'project_id' => $this->task->project_id,
            'title' => $this->task->title,
            'due_date' => $this->task->due_date?->toDateString(),
        ];
    }
}
