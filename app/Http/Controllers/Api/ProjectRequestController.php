<?php

namespace App\Http\Controllers\Api;

use App\Data\ProjectData;
use App\Data\ProjectRequestData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequestFilterRequest;
use App\Http\Requests\ProjectRequestStoreRequest;
use App\Http\Requests\ProjectRequestUpdateRequest;
use App\Http\Resources\ProjectRequestResource;
use App\Models\ProjectRequest;
use App\Services\ProjectRequestService;
use App\Services\ProjectService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mrmarchone\LaravelAutoCrud\Enums\ResponseMessages;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\JsonResponse;

class ProjectRequestController extends Controller
{
    public function __construct(
        protected ProjectRequestService $projectRequestService,
        protected ProjectService $projectService
    )
    {
    }

    public function index(ProjectRequestFilterRequest $request): AnonymousResourceCollection
    {
        $projectRequests = ProjectRequest::getQuery()
            ->where('team_id',Auth::user()->team?->id)
            ->with(['project','project.supervisor','project.media'])
            ->paginate($request->get('perPage',10))
            ->withQueryString();

        return ProjectRequestResource::collection($projectRequests)
            ->additional(['message' => ResponseMessages::RETRIEVED->message()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequestStoreRequest $request): JsonResponse
    {
        return DB::transaction(function() use ($request){
            $project = $this->projectService->store(ProjectData::from($request->validated()));
            $projectRequest = $this->projectRequestService->store($project,ProjectRequestData::from($request->validated()));

            return ProjectRequestResource::make($projectRequest->load(['team','team.students','project','project.supervisor','project.media']))
                ->additional([
                    'message' => ResponseMessages::CREATED->message()
                ])
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectRequest $projectRequest): ProjectRequestResource
    {
        return ProjectRequestResource::make($projectRequest->load(['team','team.students','project','project.supervisor','project.media','team.leader']))
            ->additional([
                'message'=>ResponseMessages::RETRIEVED->message()
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequestUpdateRequest $request, ProjectRequest $projectRequest): ProjectRequestResource
    {
        return DB::transaction(function() use ($request,$projectRequest){
            $project = $this->projectService->update(ProjectData::from($request->validated()),$projectRequest->project);
            $projectRequest = $this->projectRequestService->update(ProjectRequestData::from($request->validated()),$projectRequest);

            return ProjectRequestResource::make($projectRequest->load(['team','team.students','project','project.supervisor','project.media']))
                ->additional([
                    'message' => ResponseMessages::UPDATED
                ]);

        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectRequest $projectRequest): ProjectRequestResource
    {
        Gate::authorize('delete',$projectRequest);

        $projectRequest->project->delete();

        return ProjectRequestResource::make($projectRequest->load(['team','team.students','project','project.supervisor','project.media']))
            ->additional([
                'message' => ResponseMessages::DELETED->message()
            ]);
    }
}
