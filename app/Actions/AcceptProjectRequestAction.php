<?php

namespace App\Actions;

use App\Enums\JoinRequestStatus;
use App\Enums\ProjectStatus;
use App\Models\ProjectRequest;
use App\Models\TeamJoinRequest;
use App\Notifications\NewProjectAssignedNotification;
use App\Notifications\ProjectAcceptedNotification;
use Illuminate\Support\Facades\DB;

class AcceptProjectRequestAction
{
    public function execute(ProjectRequest $projectRequest): ProjectRequest
    {
        return DB::transaction(function () use ($projectRequest) {

            $teamId = $projectRequest->team_id;
            $project = $projectRequest->project;

            $project->update(['status' => ProjectStatus::ACTIVE]);

            ProjectRequest::where('team_id', $teamId)
                ->whereHas('project', function($q) {
                    $q->whereIn('status', [ProjectStatus::PENDING, ProjectStatus::OPEN]);
                })
                ->get()
                ->each(function ($request) {
                    $request->project()->update(['status' => ProjectStatus::REVOKED]);
                });

            TeamJoinRequest::where('team_id', $teamId)
                ->where('status', JoinRequestStatus::PENDING)
                ->update(['status' => JoinRequestStatus::REJECTED]);

            $project->supervisor->notify(new NewProjectAssignedNotification($project));

            $projectRequest->team->leader->notify(new ProjectAcceptedNotification($project));

            return $projectRequest;
        });
    }
}
