<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{

    public function view(User $user, Team $team): bool
    {
        return $team->leader_id === $user->id;
    }


    public function update(User $user, Team $team): bool
    {
        return $team->leader_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        return $team->leader_id === $user->id;
    }


}
