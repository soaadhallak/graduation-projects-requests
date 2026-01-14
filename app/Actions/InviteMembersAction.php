<?php
namespace App\Actions;

use App\Data\TeamInvitationData;
use App\Enums\TeamInvitationStatus;
use App\Events\MemberInvited;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class InviteMembersAction
{

    public function execute(Team $team,TeamInvitationData $teamInvitationData):Collection
    {
        return DB::transaction(function() use ($team, $teamInvitationData) {
            $invitations=[];

            foreach ($teamInvitationData->emails as $email) {
                $invitation=TeamInvitation::create([
                    'team_id'=>$team->id,
                    'token'=>Str::random(32),
                    'email'=>$email,
                    'status'=>TeamInvitationStatus::PENDING,
                    'expires_at'=>now()->addDays(3)
                ]);

                $user=User::where('email',$email)->first();
                event(new MemberInvited($invitation, $team->name, $user->name));

                $inviter=Auth::user();
                $user->notify(new TeamInvitationNotification($invitation,$inviter));

                $invitations[]=$invitation;
            }

            return new Collection($invitations);
        });

    }
}
