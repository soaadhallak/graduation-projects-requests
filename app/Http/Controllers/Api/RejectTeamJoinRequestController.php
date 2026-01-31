<?php

namespace App\Http\Controllers\Api;

use App\Enums\JoinRequestStatus;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\JoinRequestResource;
use App\Models\TeamJoinRequest;
use App\Notifications\JoinRequestRejectedNotification;
use Illuminate\Http\Request;

class RejectTeamJoinRequestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TeamJoinRequest $teamJoinRequest): JoinRequestResource
    {
        tap($teamJoinRequest)->update([
            'status' => JoinRequestStatus::REJECTED
        ]);

        $teamJoinRequest->user->notify(new JoinRequestRejectedNotification($teamJoinRequest));

        return JoinRequestResource::make($teamJoinRequest->load(['team','user','projectRequest']))
            ->additional([
                'message' => ResponseMessages::UPDATED->message()
            ]);
    }
}
