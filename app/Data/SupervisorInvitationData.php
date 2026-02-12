<?php

namespace App\Data;

use App\Models\SupervisorInvitation;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Data;
use Carbon\Carbon;

class SupervisorInvitationData extends Data
{
    use HasModelAttributes;
    protected static string $model = SupervisorInvitation::class;

    public function __construct(
        public ?string $email,
        public ?string $token,
        public ?Carbon $expiresAt,
        public ?Carbon $acceptedAt,
    ) {}
}
