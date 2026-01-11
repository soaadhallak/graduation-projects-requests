<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ShowMyTeamController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke():TeamResource
    {
        $user=Auth::user();
        $team=$user->student?->team;

        if(!$team){
            abort('404','You do not have a team');
        }

        $team->load(['leader.media','leader.roles','leader.permissions','students.user.media','students.user.student','leader.student']);

        return TeamResource::make($team)
        ->additional([
            'message'=>ResponseMessages::RETRIEVED->message()
        ]);

    }
}
