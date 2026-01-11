<?php

namespace App\Listeners;

use App\Events\MemberInvited;
use App\Mail\TeamInvitationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendInvitationMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MemberInvited $event): void
    {
        Mail::to($event->invitation->email)
            ->later(now()->addSeconds(5), new TeamInvitationMail($event->invitation,$event->teamName,$event->studentName));
    }
}
