<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\InviteSupervisorAction;
use App\Data\SupervisorInvitationData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupervisorInvitationStoreRequest;
use App\Http\Resources\SupervisorInvitationResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class InviteSupervisorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SupervisorInvitationStoreRequest $request,InviteSupervisorAction $inviteSupervisorAction):JsonResponse
    {
        $superInvitation = $inviteSupervisorAction->execute(SupervisorInvitationData::from($request->validated()));

        return SupervisorInvitationResource::make($superInvitation)
            ->additional([
                'message' => ResponseMessages::CREATED->message()
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
