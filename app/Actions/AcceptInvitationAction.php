<?php
namespace App\Actions;
use App\Data\AcceptInvitationData;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\InvitationAcceptedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AcceptInvitationAction
{


    public function execute(User $user,AcceptInvitationData $acceptInvitationData):Team
    {
        return DB::transaction(function() use ($user,$acceptInvitationData){
            $invitation=TeamInvitation::where('token',$acceptInvitationData->token)
                ->with(['team'])
                ->first();

            if(!$invitation){
                throw ValidationException::withMessages(['token' => 'Invalid token']);
            }

            if($invitation->expires_at->isPast()){
                $invitation->update(['status' => 'expired']);
                throw ValidationException::withMessages(['token' => 'This invitation has expired']);
            }

            if($invitation->status != 'pending'){
                throw ValidationException::withMessages(['token' => 'Invalid invitation']);
            }

            if($invitation->email != $user->email){
                throw ValidationException::withMessages(['token' => 'This invitation is for another email']);
            }

            if($user->student?->team_id){
                throw ValidationException::withMessages(['token' => 'You have a team already']);
            }

            $user->student()->update([
                'team_id' => $invitation->team_id
            ]);

            $invitation->update([
                'status' => 'accepted',
            ]);

            TeamInvitation::where('email', $user->email)
                ->where('id', '!=', $invitation->id)
                ->update(['status' => 'revoked']);

            $inviter=$invitation->team->leader;
            $inviter->notify(new InvitationAcceptedNotification($invitation));

            return $invitation->team;
        });
    }
}
