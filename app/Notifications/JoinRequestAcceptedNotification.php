<?php

namespace App\Notifications;

use App\Models\TeamJoinRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JoinRequestAcceptedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected TeamJoinRequest $joinRequest
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function withDelay(object $notifiable): array
    {
        return [
            'database'=>now()->plus(seconds:3),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'join_request_accept', 
            'message' => "Congratulations! You have been accepted into the team: " . $this->joinRequest->team->name,
            'team_id' => $this->joinRequest->team_id,
            'project_request_id' => $this->joinRequest->project_request_id,
            'user_id' => $this->joinRequest->user_id
        ];
    }
}
