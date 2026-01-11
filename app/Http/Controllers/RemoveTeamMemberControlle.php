<?php

namespace App\Http\Controllers;

use App\Actions\RemoveTeamMemberAction;
use App\Enums\ResponseMessages;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RemoveTeamMemberControlle extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Team $team,User $user,RemoveTeamMemberAction $removeTeamMemberAction):TeamResource
    {
        Gate::authorize('delete',$team);

        $team=$removeTeamMemberAction->execute($team,$user);

        return TeamResource::make($team->load('leader.media','students.user.media'))
            ->additional([
                'message'=>ResponseMessages::UPDATED->message()
            ]);
    }
}
