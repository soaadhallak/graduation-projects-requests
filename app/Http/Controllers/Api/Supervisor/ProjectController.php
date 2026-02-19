<?php

namespace App\Http\Controllers\Api\Supervisor;

use App\Data\ProjectData;
use App\Enums\ProjectStatus;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteProjectRequest;
use App\Http\Resources\ProjectRequestResource;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    )
    {
    }


    public function index(): AnonymousResourceCollection
    {
        $projectRequests = ProjectRequest::whereHas('project', function ($query) {
            $query->where('supervisor_id', Auth::id())
              ->whereIn('status', [ProjectStatus::ACTIVE, ProjectStatus::INPROGRESS]);
        })
        ->with(['project.media', 'team.students'])
        ->paginate(10);

        return ProjectRequestResource::collection($projectRequests)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);
    }

    public function markAsReviewed(ProjectRequest $projectRequest): ProjectResource
    {
        Gate::authorize('update', $projectRequest->project);

        $project = $this->projectService->update(ProjectData::from([
            'status' => ProjectStatus::INPROGRESS
        ]),$projectRequest->project);

        return ProjectResource::make($project)
            ->additional([
                'message' => ResponseMessages::UPDATED->message()
            ]);
    }

    public function complete(CompleteProjectRequest $request, ProjectRequest $projectRequest): ProjectResource
    {
        $project = $this->projectService->update(ProjectData::from($request->validated()),$projectRequest->project);

        return ProjectResource::make($project)
            ->additional([
                'message' => ResponseMessages::UPDATED->message()
            ]);
    }
}
