<?php

namespace App\Data;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class ProjectData extends Data
{
    use HasModelAttributes;
    protected static string $model = Project::class;

    public function __construct(
        #[Max(255),Unique('projects','title')]
        public ?string $title,
        #[Max(255),Min(25)]
        public ?string $description,
        #[Max(255)]
        public ?string $tools,
        #[Exists('users','id')]
        public ?int $supervisorId,
        #[Max(5),Min(1)]
        public ?array $files,
        public ?ProjectStatus $status,
        public ?string $adminRejectionReason,
        public ?float $grade

    ) {}
}
