<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamInvitationResource;
use App\Models\Team;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ShowTeamInvitationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Team $team):AnonymousResourceCollection
    {
        Gate::authorize('view',$team);

        $invitations=$team->invitations()->with(['user.media'])->latest()->get();

        return TeamInvitationResource::collection($invitations)
            ->additional([
                'message'=>ResponseMessages::RETRIEVED->message()
            ]);
    }
}
