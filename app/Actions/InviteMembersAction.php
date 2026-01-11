<?php
namespace App\Actions;

use App\Data\TeamInvitationData;
use App\Events\MemberInvited;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class InviteMembersAction
{

    public function execute(Team $team,TeamInvitationData $teamInvitationData):Collection
    {
        $emails=$teamInvitationData->emails;
        $currentMembersCount=$team->students()?->count();
        $newInvitesCount=count($emails);
        $invitesForThisTeam=$team->invitations()->where('status','pending')->where('expires_at', '>', now())->whereNotIn('email', $emails)->count();

        if($currentMembersCount + $newInvitesCount + $invitesForThisTeam > 8){
            throw ValidationException::withMessages([
                'team'=>'Your team has 8 members, you can not send any more invitations'
            ]);
        }

        $usersToInvitations=User::with('student')->whereIn('email',$emails)->get()->keyBy('email');

        $usersHaveInvitationToThisTeam=$team->invitations()
            ->whereIn('email', $emails)
            ->whereIn('status', ['pending', 'accepted'])
            ->pluck('email')
            ->toArray();

        $errors=[];
        $validEmails=[];

        foreach($emails as $email){
            $userEmail=$usersToInvitations->get($email);

            if($email === $team->leader?->email){
                $errors[$email]="You can not invite yourself";
                continue;
            }

            if($userEmail?->student?->team_id){
                $errors[$email]='This email have team already';
                continue;
            }

            if(in_array($email, $usersHaveInvitationToThisTeam)){
                $errors[$email]='You send invitation to this email already';
                continue;
            }
            $validEmails[]=$email;
        }

        if(!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        return DB::transaction(function() use ($team, $usersToInvitations, $validEmails) {
            $invitations=[];

            foreach ($validEmails as $email) {
                $invitation=TeamInvitation::create([
                    'team_id'=>$team->id,
                    'token'=>Str::random(32),
                    'email'=>$email,
                    'status'=>'pending',
                    'expires_at'=>now()->addDays(3)
                ]);

                $user=$usersToInvitations->get($email);
                event(new MemberInvited($invitation, $team->name, $user->name));

                $inviter=Auth::user();
                $user->notify(new TeamInvitationNotification($invitation,$inviter));

                $invitations[]=$invitation;
            }

            return new Collection($invitations);
        });

    }
}
