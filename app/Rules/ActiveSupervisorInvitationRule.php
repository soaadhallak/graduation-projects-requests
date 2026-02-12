<?php

namespace App\Rules;

use App\Models\SupervisorInvitation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ActiveSupervisorInvitationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = SupervisorInvitation::where('email', $value)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->exists();

        if ($exists) {
            $fail(__('An active invitation has already been sent to this email address'));
        }
    }
}
