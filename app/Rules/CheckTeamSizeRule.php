<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class CheckTeamSizeRule implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $numberOfMembers = Auth::user()->team?->students()?->count() ?? 0;

        if($numberOfMembers < 4 && !$value){
            $fail(__('You must activate lookingForMembers because your team size is less than 4'));
        }
    }
}
