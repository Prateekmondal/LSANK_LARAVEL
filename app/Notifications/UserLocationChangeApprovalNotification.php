<?php

namespace App\Notifications;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserLocationChangeApprovalNotification extends Notification
{
    // use Queueable;

    protected User $user;
    protected ?Tenant $tenant;
    protected ?string $previousTenantId;

    public function __construct(User $user, ?Tenant $tenant = null, ?string $previousTenantId = null)
    {
        $this->user = $user;
        $this->tenant = $tenant;
        $this->previousTenantId = $previousTenantId;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $tenantName = $this->tenant ? ($this->tenant->name ?? $this->tenant->id) : ($this->user->tenant_id ?? 'unknown');
        $previousLocation = $this->previousTenantId ? ucfirst($this->previousTenantId) : 'previous location';
        $locationLabel = ucfirst($tenantName);

        return (new MailMessage)
            ->subject('User location change requires approval')
            ->greeting('Hello ' . ($notifiable->name ?? 'Admin') . ',')
            ->line('The user ' . $this->user->name . ' (' . $this->user->email . ') has changed their assigned location.')
            ->line('New location: ' . $locationLabel)
            ->when($this->previousTenantId, fn (MailMessage $mail) => $mail->line('Previous location: ' . ucfirst($this->previousTenantId)))
            ->line('The user has been marked as pending approval and must be activated before they can sign in from the new subdomain.')
            ->action('Review User', url('/admin/users/' . $this->user->id))
            ->line('Please approve the user to allow login from the new tenant subdomain.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'user_location_change_approval',
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'tenant_id' => $this->user->tenant_id,
            'tenant_name' => $this->tenant ? ($this->tenant->name ?? $this->tenant->id) : null,
            'previous_tenant_id' => $this->previousTenantId,
            'message' => 'User ' . $this->user->name . ' changed location and requires approval for the new tenant.',
            'link' => url('/admin/users/' . $this->user->id),
        ];
    }
}
