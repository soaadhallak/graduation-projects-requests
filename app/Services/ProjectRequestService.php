<?php
namespace App\Services;

use App\Data\ProjectRequestData;
use App\Enums\TeamInvitationStatus;
use App\Models\Project;
use App\Models\ProjectRequest;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProjectRequestService{

    public function store(Project $project,ProjectRequestData $data):ProjectRequest
    {
        $team = Auth::user()->team;

        return DB::transaction(function() use ($data,$project,$team){
            $projectRequest = $project->projectRequests()->create([
                'team_id' => $team->id,
                'project_id' => $project->id,
                'is_looking_for_members' => $data->isLookingForMembers ?? false,
                'max_number' => $data->isLookingForMembers ? $data->maxNumber : null
            ]);

            $this->revokePendingInvitations($team);

            return $projectRequest;
        });
    }

    public function update(ProjectRequestData $data,ProjectRequest $projectRequest):ProjectRequest
    {
        return DB::transaction(function() use ($data,$projectRequest){
            tap($projectRequest)->update($data->onlyModelAttributes());

            return $projectRequest;
        });
    }

    private function revokePendingInvitations(Team $team): void
    {
        $team->invitations()->where('status',TeamInvitationStatus::PENDING)->update([
            'status'=>TeamInvitationStatus::REVOKED
        ]);
    }

}
