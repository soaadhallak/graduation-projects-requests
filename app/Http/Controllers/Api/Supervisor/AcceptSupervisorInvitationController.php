<?php

namespace App\Http\Controllers\Api\Supervisor;

use App\Actions\AcceptSupervisorInvitationAction;
use App\Data\UserData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupervisorStoreRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AcceptSupervisorInvitationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SupervisorStoreRequest $request,AcceptSupervisorInvitationAction $acceptSupervisor):JsonResponse
    {
        $supervisor = $acceptSupervisor->execute(UserData::from($request->validated()),$request->token);

        return UserResource::make($supervisor['user']->load(['media','roles','permissions']))
            ->additional([
                'message' => ResponseMessages::CREATED->message(),
                'token'=>$supervisor['token']
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
