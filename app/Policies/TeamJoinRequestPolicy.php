<?php

namespace App\Policies;

use App\Models\TeamJoinRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamJoinRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->id === $user->team?->leader_id;
    }

}
