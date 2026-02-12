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
        $invitation = SupervisorInvitation::where('token', $value)->firstOrFail();

        if ($invitation->isExpired() || $invitation->isAccepted()) {
            $fail(__('Invalid token'));
        }
    }
}
