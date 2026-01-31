<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class CheckMaxMembersRule implements ValidationRule
{


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $numberOfTeamMembers = Auth::user()->team?->students()->count() ?? 0;

        if($numberOfTeamMembers + $value > 8){
            $remaining = 8 - $numberOfTeamMembers;

            $fail(__('Your team has (:current) members you can only look for up to (:remaining) more students',[
                'current' => $numberOfTeamMembers,
                'remaining' => $remaining
            ]));
        }
    }
}
