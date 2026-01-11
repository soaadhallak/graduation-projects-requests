<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class TeamInvitationData extends Data
{
    public function __construct(
        public ?array $emails
    ) {}
}
