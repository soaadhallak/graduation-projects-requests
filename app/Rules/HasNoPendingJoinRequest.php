<?php

namespace App\Rules;

use App\Enums\JoinRequestStatus;
use App\Models\TeamJoinRequest;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class HasNoPendingJoinRequest implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $hasPendingJoinRequestsForSameTeam = TeamJoinRequest::where('user_id',Auth::user()->id)
            ->where('team_id',$value)->where('status',JoinRequestStatus::PENDING)->exists();

        if($hasPendingJoinRequestsForSameTeam){
            $fail(__("You have a join request pending for this team"));
        }
    }
}
