<?php

namespace App\Data;

use App\Models\Team;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class TeamData extends Data
{
    use HasModelAttributes;
    protected static string $model=Team::class;

    public function __construct(
        #[Max(255),Unique('teams','name')]
        public ?string $name,
    ) {}
}
