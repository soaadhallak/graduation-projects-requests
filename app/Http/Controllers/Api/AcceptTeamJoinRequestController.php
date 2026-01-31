<?php

namespace App\Http\Controllers\Api;

use App\Actions\AcceptTeamJoinRequestAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptJoinRequestTeamRequest;
use App\Http\Resources\JoinRequestResource;
use App\Models\TeamJoinRequest;
use Illuminate\Http\Request;
use Mrmarchone\LaravelAutoCrud\Enums\ResponseMessages;

class AcceptTeamJoinRequestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TeamJoinRequest $teamJoinRequest, AcceptJoinRequestTeamRequest $request,AcceptTeamJoinRequestAction $acceptTeamJoinRequestAction): JoinRequestResource
    {
        $joinRequest = $acceptTeamJoinRequestAction->execute($teamJoinRequest);

        return JoinRequestResource::make($joinRequest->load(['user','team','projectRequest']))
            ->additional([
                'message' => ResponseMessages::UPDATED->message()
            ]);
    }
}
