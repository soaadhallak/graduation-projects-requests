<?php
namespace App\Actions;
use App\Data\AcceptInvitationData;
use App\Enums\TeamInvitationStatus;
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


            $user->student()->update([
                'team_id' => $invitation->team_id
            ]);

            $invitation->update([
                'status' => TeamInvitationStatus::ACCEPTED,
            ]);

            TeamInvitation::where('email', $user->email)
                ->where('id', '!=', $invitation->id)
                ->update(['status' => TeamInvitationStatus::REVOKED]);

            $inviter=$invitation->team->leader;
            $inviter->notify(new InvitationAcceptedNotification($invitation));

            return $invitation->team;
        });
    }
}
