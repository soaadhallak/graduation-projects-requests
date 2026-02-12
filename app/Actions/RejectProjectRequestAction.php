<?php

namespace App\Actions;

use App\Data\ProjectData;
use App\Models\ProjectRequest;
use App\Enums\ProjectStatus;
use App\Notifications\ProjectRejectedNotification;
use Illuminate\Support\Facades\DB;

class RejectProjectRequestAction
{
    public function execute(ProjectRequest $projectRequest, ProjectData $projectData): ProjectRequest
    {
        return DB::transaction(function () use ($projectRequest, $projectData) {
            $projectRequest->project()->update($projectData->onlyModelAttributes());

            $leader = $projectRequest->team?->leader;
            $leader->notify(new ProjectRejectedNotification($projectRequest->project));

            return $projectRequest;
        });
    }
}
