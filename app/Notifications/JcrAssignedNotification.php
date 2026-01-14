<?php

namespace App\Notifications;

use App\Models\Jcr;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class JcrAssignedNotification extends Notification
{
    use Queueable;

    protected Jcr $jcr;
    protected $assignedBy;

    public function __construct(Jcr $jcr, $assignedBy = null)
    {
        $this->jcr = $jcr;
        $this->assignedBy = $assignedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        $url = route('jcr.show', $this->jcr->id);

        return (new MailMessage)
            ->subject('Assigned as Party Chief for JCR #' . $this->jcr->id)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line(($this->assignedBy ? $this->assignedBy->name : 'A user') . ' has assigned you as Party Chief for the JCR:')
            ->line("JCR: {$this->jcr->fieldName} - {$this->jcr->wellNo} (ID: {$this->jcr->id})")
            ->action('View JCR', $url)
            ->line('You can sign this JCR once you have reviewed it.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'You have a JCR to sign from ' . $this->assignedBy->name,
            'link' => route('jcr.show', $this->jcr->id),
        ];
    }
}