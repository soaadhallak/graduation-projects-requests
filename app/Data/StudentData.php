<?php

namespace App\Data;

use App\Models\Student;
use Mrmarchone\LaravelAutoCrud\Traits\HasModelAttributes;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class StudentData extends Data
{
    use HasModelAttributes;
    protected static string $model = Student::class;

    public function __construct(
        #[Max(10),Unique('students','university_number')]
        public string $universityNumber,
        #[Max(255)]
        public string $skills,
        #[Exists('majors','id')]
        public int $majorId,
        #[Exists('teams','id')]
        public ?int $teamId,
    ) {}
}
