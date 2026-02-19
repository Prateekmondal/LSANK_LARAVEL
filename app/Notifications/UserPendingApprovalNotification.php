<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPendingApprovalNotification extends Notification implements ShouldQueue
{
	use Queueable;

	protected User $newUser;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(User $newUser)
	{
		$this->newUser = $newUser;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array<int, string>
	 */
	public function via($notifiable): array
	{
		return ['mail', 'database'];
	}

	/**
	 * Get the mail representation of the notification.
	 */
	public function toMail($notifiable): MailMessage
	{
		$registeredAt = null;
		if (! empty($this->newUser->created_at) && method_exists($this->newUser->created_at, 'format')) {
			$registeredAt = $this->newUser->created_at->format('d-m-Y H:i:s');
		} elseif (! empty($this->newUser->created_at)) {
			$registeredAt = (string) $this->newUser->created_at;
		} else {
			$registeredAt = now()->format('d-m-Y H:i:s');
		}

		return (new MailMessage)
			->subject('New user registration — approval required')
			->greeting('Hello ' . ($notifiable->name ?? 'Admin') . ',')
			->line('A new user has registered and requires your approval.')
			->line('Name: ' . $this->newUser->name)
			->line('Email: ' . $this->newUser->email)
			->line('CPF: ' . $this->newUser->cpf)
			->line('Registered: ' . $registeredAt)
			->action('Review & Approve user', url('/admin/resources/users'))
			->line('Please log in to the admin panel and approve or reject this user.');
	}

	/**
	 * Get the array representation of the notification for database storage.
	 *
	 * @param  mixed  $notifiable
	 * @return array<string, mixed>
	 */
	public function toArray($notifiable): array
	{
		return [
			'type' => 'user_pending_approval',
			'user_id' => $this->newUser->id,
			'user_name' => $this->newUser->name,
			'user_email' => $this->newUser->email,
			'user_cpf' => $this->newUser->cpf,
			'message' => 'New user ' . $this->newUser->name . ' (' . $this->newUser->email . ') is pending approval.',
			'action_url' => url('/admin/resources/users'),
		];
	}
}

