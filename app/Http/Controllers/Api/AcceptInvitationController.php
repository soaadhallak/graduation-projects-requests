<?php

namespace App\Http\Controllers\Api;

use App\Actions\AcceptInvitationAction;
use App\Data\AcceptInvitationData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptInvitationRequest;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptInvitationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AcceptInvitationRequest $request,AcceptInvitationAction $acceptInvitationAction):TeamResource
    {
        $invitation=$acceptInvitationAction->execute(Auth::user(),AcceptInvitationData::from($request->validated()));

        return TeamResource::make($invitation->load(['leader.media','students.user','students.user.media','students.user.student','leader.student']))
            ->additional([
                'message'=>ResponseMessages::RETRIEVED->message()
            ]);
    }
}
