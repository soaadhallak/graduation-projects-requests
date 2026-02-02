<?php

namespace App\Data;

use App\Enums\JoinRequestStatus;
use App\Models\TeamJoinRequest;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class JoinRequestData extends Data
{
    use HasModelAttributes;
    protected static string $model = TeamJoinRequest::class;
    
    public function __construct(
        #[Exists('teams','id')]
        public ?int $teamId,
        public ?int $projectRequestId,
        public ?JoinRequestStatus $status = JoinRequestStatus::PENDING,
    ) {}
}
