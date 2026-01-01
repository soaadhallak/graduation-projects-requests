<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class PasswordData extends Data
{
    public function __construct(
        #[Exists('users','email')]
        public ?string $email,
        public ?string $token,
        public ?string $password,
        #[MapInputName('password_confirmation')]
        public ?string $passwordConfirmation,
    ) {}
}
