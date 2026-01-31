<?php

namespace App\Data;

use App\Models\ProjectRequest;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Data;

class ProjectRequestData extends Data
{
    use HasModelAttributes;
    protected static string $model = ProjectRequest::class;

    public function __construct(
        public ?bool $isLookingForMembers,
        public ?int $maxNumber,
    ) {}
}
