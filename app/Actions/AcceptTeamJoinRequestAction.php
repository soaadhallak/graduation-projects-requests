<?php
namespace App\Actions;

use App\Enums\JoinRequestStatus;
use App\Enums\ProjectStatus;
use App\Models\TeamJoinRequest;
use App\Notifications\JoinRequestAcceptedNotification;
use Illuminate\Support\Facades\DB;

class AcceptTeamJoinRequestAction
{
    public function execute(TeamJoinRequest $teamJoinRequest): TeamJoinRequest
    {
        return DB::transaction(function() use ($teamJoinRequest){
            $projectRequest = $teamJoinRequest->projectRequest;
            $projectRequest->decrement('max_number');

            if($projectRequest->max_number === 0){

                $projectRequest->project()->update([
                    'status' => ProjectStatus::PENDING
                ]);

                $projectRequest->update([
                    'is_looking_for_members' => false,
                    'max_number' => 0
                ]);
            }

            tap($teamJoinRequest)->update([
                'status' => JoinRequestStatus::ACCEPTED
            ]);

            $teamJoinRequest->user->student()->update([
                'team_id' => $teamJoinRequest->team_id
            ]);

            TeamJoinRequest::where('user_id',$teamJoinRequest->user_id)
                ->where('status', JoinRequestStatus::PENDING)
                ->update(['status' => JoinRequestStatus::REJECTED]);

            $teamJoinRequest->user->notify(new JoinRequestAcceptedNotification($teamJoinRequest));

            return $teamJoinRequest;
        });
    }
}
