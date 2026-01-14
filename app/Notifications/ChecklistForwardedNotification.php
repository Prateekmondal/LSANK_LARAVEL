<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChecklistForwardedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $forward;

    public function __construct($forward)
    {
        $this->forward = $forward;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Checklist Forwarded for Your Signature')
            ->line('You have received a checklist to sign.')
            ->action('View Checklist', route('checklists.show', $this->forward->checklist_id))
            ->line('Message: ' . $this->forward->message);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'You have a checklist to sign from ' . $this->forward->fromUser->name,
            'link' => route('checklists.show', $this->forward->checklist_id),
        ];
    }
}