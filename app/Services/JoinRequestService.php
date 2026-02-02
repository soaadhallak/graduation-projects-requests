<?php
namespace App\Services;

use App\Data\JoinRequestData;
use App\Models\TeamJoinRequest;
use App\Models\User;
use App\Notifications\JoinRequestSentNotification;
use Illuminate\Support\Facades\DB;

class JoinRequestService
{
    public function store(JoinRequestData $data,User $user): TeamJoinRequest
    {
        return DB::transaction(function() use ($data,$user){
            $joinRequest = $user->joinRequests()->create($data->onlyModelAttributes());

            $leader = $joinRequest->team->leader;
            $leader->notify(new JoinRequestSentNotification($joinRequest));

            return $joinRequest;
        });
    }
}
