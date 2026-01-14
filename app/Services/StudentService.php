<?php

namespace App\Services;

use App\Data\StudentData;
use App\Http\Requests\RemoveMemberRequest;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function update(Student $student,StudentData $studentData):Student
    {
        return DB::transaction(function() use ($student,$studentData){

            tap($student)->update($studentData->onlyModelAttributes());

            return $student;
        });
    }
}
