<?php

namespace App\Rules;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InviteMemberRule implements ValidationRule
{
    public function __construct(
        protected Team $team
    )
    {
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userHaveInvitationToThisTeam=$this->team->invitations()
            ->where('email', $value)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        $user=User::where('email',$value)->first();

        if($value === $this->team->leader?->email){
            $fail(__('You can not invite yourself'));
            return;
        }

        if($user?->student?->team_id){
            $fail(__('This email have team already'));
            return;
        }

        if($userHaveInvitationToThisTeam){
            $fail(__('You send invitation to this email already'));
            return;
        }
    }
}
