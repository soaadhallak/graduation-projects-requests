<?php

namespace App\Http\Controllers\Api;

use App\Data\TeamData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TeamController extends Controller
{
    public function __construct(
        protected TeamService $teamService
    )
    {
    }

    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamStoreRequest $request):JsonResponse
    {
        $team=$this->teamService->store(TeamData::from($request->validated()),Auth::user());
        return TeamResource::make($team->load(['leader.media','leader.permissions','students.user.media','students.user.student','leader.student']))
        ->additional([
            'message'=>ResponseMessages::CREATED->message()
        ])
        ->response()
        ->setStatusCode(Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team):TeamResource
    {
        Gate::authorize('view',$team);

        return TeamResource::make($team->load(['leader.media','leader.permissions','students.user.media','students.user.student','leader.student']))
        ->additional([
            'message'=>ResponseMessages::RETRIEVED->message()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamUpdateRequest $request, Team $team):TeamResource
    {
        Gate::authorize('update',$team);

        $team=$this->teamService->update(TeamData::from($request->validated()),$team);

        return TeamResource::make($team->load(['leader.media','leader.permissions','students.user.media','students.user.student','leader.student']))
        ->additional([
            'message'=>ResponseMessages::UPDATED->message()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team):TeamResource
    {
        Gate::authorize('delete',$team);

        $team->delete();

        return TeamResource::make($team->load(['leader.media','leader.permissions','students.user.media','students.user.student','leader.student']))
        ->additional([
            'message' => ResponseMessages::DELETED->message()
        ]);

    }
}
