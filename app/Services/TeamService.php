<?php
namespace App\Services;

use App\Data\TeamData;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeamService
{
    public function store(TeamData $teamData,User $user):Team
    {
        return DB::transaction(function() use ($teamData,$user)
        {
            $team=Team::create([
                'name'=>$teamData->name,
                'leader_id'=>$user->id
            ]);

            $user->givePermissionTo([
                'submit project request',
                'edit project request',
                'delete project request',
                'remove team member',
                'invite Members'
            ]);

            $user->student()->update([
                'team_id'=>$team->id
            ]);

            return $team;
        });

    }

    public function update(TeamData $teamData,Team $team):Team
    {
        return DB::transaction(function() use ($teamData,$team){
            tap($team)->update([
                'name'=>$teamData->name
            ]);

            return $team;
        });
    }
}
