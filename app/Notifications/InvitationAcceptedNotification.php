<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationAcceptedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected $invitation
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
        $newMemberName=$this->invitation->user->name;

        return [
            'team_id'=>$this->invitation->team_id,
            'member_id'=>$this->invitation->user->id,
            'member_name'=>$newMemberName,
            'message'=>$newMemberName . ' accepted your invitation and joined to your team'
        ];
    }
}
