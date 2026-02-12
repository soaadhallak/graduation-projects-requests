<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\ProjectData;
use App\Enums\ProjectStatus;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectFilterRequest;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    )
    {
    }


    public function index(ProjectFilterRequest $request): AnonymousResourceCollection
    {
        $projects = Project::getQuery()
            ->whereIn("status",[ProjectStatus::ARCHIVED])
            ->with(['supervisor','media'])
            ->paginate($request->get('perPage',10))
            ->withQueryString();

        return ProjectResource::collection($projects)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectStoreRequest $request): JsonResponse
    {
        $project = $this->projectService->store(ProjectData::from($request->validated()));

        return ProjectResource::make($project->load(['supervisor','media']))
            ->additional([
                'message' => ResponseMessages::CREATED->message()
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): ProjectResource
    {
        return ProjectResource::make($project->load(['supervisor','media']))
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectUpdateRequest $request, Project $project): ProjectResource
    {
        $project = $this->projectService->update(ProjectData::from($request->validated()),$project);

        return ProjectResource::make($project->load(['supervisor','media']))
            ->additional([
                'message' => ResponseMessages::UPDATED->message()
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): ProjectResource
    {
        Gate::authorize('delete',$project);

        $project->delete();

        return ProjectResource::make($project->load(['supervisor','media']))
            ->additional([
                'message' => ResponseMessages::DELETED->message()
            ]);
    }
}
