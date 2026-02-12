<?php

namespace App\Rules;

use App\Models\SupervisorInvitation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckSupervisorInvitationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $invitation = SupervisorInvitation::where('token', $value)->first();

        if (!$invitation) {
            $fail(__('Invalid token'));
            return;
        }


        if ($invitation->isExpired()) {
            $fail(__('This invitation has expired'));
            return;
        }

        if ($invitation->isAccepted()) {
            $fail(__('This invitation has already been accepted'));
        }
    }
}
