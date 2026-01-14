<?php

namespace App\Http\Controllers\Api;

use App\Actions\InviteMembersAction;
use App\Data\TeamInvitationData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamInvitationStoreRequest;
use App\Http\Resources\TeamInvitationResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class InviteMembersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TeamInvitationStoreRequest $request,Team $team,InviteMembersAction $inviteMemberAction):JsonResponse
    {
        Gate::authorize('invite',$team);
        $invitations=$inviteMemberAction->execute($team,TeamInvitationData::from($request->validated()));

        return TeamInvitationResource::collection($invitations->load(['team', 'user.media']))
            ->additional([
                'message'=>ResponseMessages::CREATED->message()
            ])->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
