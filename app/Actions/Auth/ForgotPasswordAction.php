<?php
namespace App\Actions\Auth;

use App\Data\PasswordData;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordAction
{

    public function execute(PasswordData $passwordData):string
    {
        $status=Password::sendResetLink($passwordData->toArray());

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages(['email' => [__($status)]]);
        }

        return __($status);
    }

}
