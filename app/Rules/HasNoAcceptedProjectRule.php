<?php

namespace App\Rules;

use App\Enums\ProjectStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class HasNoAcceptedProjectRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $projectAccepted = Auth::user()->team()?->whereHas('projects',function($query){
            $query->whereNotIn('status',[ProjectStatus::PENDING,ProjectStatus::OPEN,ProjectStatus::REJECTED]);
        })->exists();

        if($projectAccepted){
            $fail(__('You have accepted project already'));
        }
    }
}
