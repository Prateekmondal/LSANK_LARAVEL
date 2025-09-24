<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ExplosiveChecklist;
use App\Models\User;

class ChecklistApprovalNotification extends Notification
{
    // use Queueable;

    public $checklist;
    public $creator;

    public function __construct(ExplosiveChecklist $checklist, User $creator)
    {
        $this->checklist = $checklist;
        $this->creator = $creator;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Checklist Requires Your Approval: ' . $this->checklist->type_name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->creator->name . ' has submitted a checklist for your approval.')
            ->line('**Checklist Details:**')
            ->line('- Type: ' . $this->checklist->type_name)
            ->line('- Well Name: ' . $this->checklist->well_name)
            ->line('- Date: ' . $this->checklist->date->format('Y-m-d'))
            ->action('Review Checklist', url(route('checklists.show', $this->checklist->id)))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->creator->name . ' has requested your approval for a checklist',
            'link' => route('checklists.show', $this->checklist->id),
            'checklist_id' => $this->checklist->id,
            'creator_name' => $this->creator->name,
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->creator->name . ' requested approval for ' . $this->checklist->type_name,
            'link' => route('checklists.show', $this->checklist->id),
            'checklist_id' => $this->checklist->id,
            'creator_name' => $this->creator->name,
            'type' => 'checklist_approval',
            'icon' => 'fa-clipboard-check',
        ];
    }
}