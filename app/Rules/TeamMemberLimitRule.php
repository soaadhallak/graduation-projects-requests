<?php

namespace App\Rules;

use App\Models\Team;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TeamMemberLimitRule implements ValidationRule
{
    public function __construct(
        protected Team $team
    )
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentMembersCount=$this->team->students()?->count();
        $newInvitesCount=count($value);
        $invitesForThisTeam=$this->team->invitations()
            ->where('status','pending')
            ->where('expires_at', '>', now())
            ->whereNotIn('email', $value)
            ->count();

        if($currentMembersCount + $newInvitesCount + $invitesForThisTeam > 8){
            $fail(__('Your team has 8 members, you can not send any more invitations'));
        }
    }
}
