<?php
namespace App\Actions;

use App\Models\Team;
use App\Models\User;
use App\Notifications\TeamMemberRemovedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RemoveTeamMemberAction
{

    public function execute(Team $team,User $user):Team
    {
        if($team->leader_id === $user->id){
            throw ValidationException::withMessages([
                'member'=>'You try remove yourself'
            ]);
        }

        if($user->student?->team_id != $team->id){
            throw ValidationException::withMessages([
                'member'=>'This member have another time'
            ]);
        }

        return DB::transaction(function() use ($user,$team){

            $user->student()?->update([
                'team_id'=>null
            ]);

            $user->notify(new TeamMemberRemovedNotification($team));

            return $team;
        });


    }
}
