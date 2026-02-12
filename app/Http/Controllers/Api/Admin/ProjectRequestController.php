<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\ProjectData;
use App\Enums\ProjectStatus;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequestFilterRequest;
use App\Http\Requests\ProjectRequestSupervisorUpdateRequest;
use App\Http\Resources\ProjectRequestResource;
use App\Models\ProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProjectRequestFilterRequest $request):AnonymousResourceCollection
    {
        $projectRequests = ProjectRequest::getQuery()
            ->whereHas('project',function($query){
                $query->whereIn('status',[
                    ProjectStatus::PENDING,
                    ProjectStatus::ACTIVE,
                    ProjectStatus::REJECTED]);
            })->with(['project.supervisor','project.media','team.students.user.student.major'])
            ->paginate($request->get('perPage',10))
            ->withQueryString();

        return ProjectRequestResource::collection($projectRequests)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectRequest $adminProjectRequest): ProjectRequestResource
    {
        return ProjectRequestResource::make($adminProjectRequest->load(['project.supervisor','project.media','team.students.user.student.major']))
            ->additional([
                'message'=>ResponseMessages::RETRIEVED->message()
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequestSupervisorUpdateRequest $request, ProjectRequest $adminProjectRequest, ProjectService $projectService): ProjectRequestResource
    {
        $projectRequest = $projectService->update(ProjectData::from($request->validated()),$adminProjectRequest->project);

        return ProjectRequestResource::make($adminProjectRequest->load(['project.supervisor','project.media','team.students']))
            ->additional([
                'message' => ResponseMessages::UPDATED->message()
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
