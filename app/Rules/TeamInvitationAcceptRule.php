<?php

namespace App\Rules;

use App\Enums\TeamInvitationStatus;
use App\Models\TeamInvitation;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\ValidationException;

class TeamInvitationAcceptRule implements ValidationRule
{
    public function __construct(
        protected User $user
    )
    {
    }


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $invitation=TeamInvitation::where('token',$value)->first();

        if(!$invitation){
            $fail(__('Invalid token'));
            return;
        }

        if($invitation->expires_at->isPast()){
            $fail(__('This invitation has expired'));
            return;
        }

        if($invitation->status !== TeamInvitationStatus::PENDING){
            $fail(__('Invalid invitation'));
            return;
        }

        if($invitation->email !== $this->user->email){
            $fail(__('This invitation is for another email'));
            return;
        }

        if($this->user->student?->team_id){
            $fail(__('You have a team already'));
            return;
        }
    }
}
