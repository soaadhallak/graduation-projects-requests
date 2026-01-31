<?php

namespace App\Policies;

use App\Enums\ProjectStatus;
use App\Models\ProjectRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectRequestPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->id === $user->team?->leader_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectRequest $projectRequest): bool
    {
        $isValidProject = in_array($projectRequest->project->status,[ProjectStatus::PENDING,ProjectStatus::OPEN]);
        return $user->id === $projectRequest->team->leader_id && $isValidProject  ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectRequest $projectRequest): bool
    {
        $isValidProject = in_array($projectRequest->project->status,[ProjectStatus::PENDING,ProjectStatus::OPEN]);
        return $user->id === $projectRequest->team->leader_id && $isValidProject;
    }


}
