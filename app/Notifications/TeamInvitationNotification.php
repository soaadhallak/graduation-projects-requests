<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class TeamInvitationNotification extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected $invitation,
        protected $inviter
    )
    {
        //
    }

    public function withDelay(object $notifiable): array
    {
        return [
            'database'=>now()->plus(seconds:3),
        ];
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


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $teamName=$this->invitation->team->name;

        return [
            'type'=>'team_invitation',
            'team_id'=>$this->invitation->team_id,
            'team_name'=>$teamName,
            'invited_by_user_id'=>$this->inviter->id,
            'token'=>$this->invitation->token,
            'message'=>'You have been invited to join the team:'. $teamName .' by ' . $this->inviter->name
        ];
    }
}
