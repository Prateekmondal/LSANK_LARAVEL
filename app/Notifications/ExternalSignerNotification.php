<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ExplosiveChecklist;

class ExternalSignerNotification extends Notification
{

    public $checklist;
    public $signUrl;

    public function __construct(ExplosiveChecklist $checklist)
    {
        $this->checklist = $checklist;
        $this->signUrl = route('external-signer.show', $checklist->id);
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Request for Signature: Checklist B - ' . $this->checklist->well_name)
            ->greeting('Dear Sir/Madam,')
            ->line('You have been requested to sign off on Checklist B for the following operation:')
            ->line('**Well Name:** ' . $this->checklist->well_name)
            ->line('**Job Type:** ' . $this->checklist->job_type)
            ->line('**Date:** ' . $this->checklist->date->format('Y-m-d'))
            ->line('Please click the button below to provide your signature and details:')
            ->action('Sign Checklist', $this->signUrl)
            ->line('This link will expire in 7 days.')
            ->line('Thank you for your cooperation.');
    }
}