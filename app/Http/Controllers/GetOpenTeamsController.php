<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Enums\ResponseMessages;
use App\Http\Requests\ProjectRequestFilterRequest;
use App\Http\Resources\ProjectRequestResource;
use App\Models\ProjectRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class getOpenTeamsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ProjectRequestFilterRequest $request): AnonymousResourceCollection
    {
        $projectRequests = ProjectRequest::getQuery()
            ->whereHas('project',function($q){
                $q->where('status',ProjectStatus::OPEN);
            })
            ->where('is_looking_for_members', true)
            ->with(['project','team','team.students','team.leader','project.supervisor','project.media'])
            ->paginate($request->get('perPage',6))
            ->withQueryString();

        return ProjectRequestResource::collection($projectRequests)
            ->additional(['message' => ResponseMessages::RETRIEVED->message()]);


    }
}
