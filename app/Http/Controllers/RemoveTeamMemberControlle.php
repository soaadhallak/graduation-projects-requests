<?php

namespace App\Http\Controllers;

use App\Actions\RemoveTeamMemberAction;
use App\Data\StudentData;
use App\Enums\ResponseMessages;
use App\Http\Requests\RemoveMemberRequest;
use App\Http\Resources\TeamResource;
use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use App\Notifications\TeamMemberRemovedNotification;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RemoveTeamMemberControlle extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Team $team,Student $student,StudentService $studentService,RemoveMemberRequest $request):TeamResource
    {
        Gate::authorize('removeMember',[$team,$student]);

        $student=$studentService->update($student,StudentData::from(['teamId'=>null]));

        $student->notify(new TeamMemberRemovedNotification($team));

        return TeamResource::make($team->load('leader.media','students.user.media'))
            ->additional([
                'message'=>ResponseMessages::UPDATED->message()
            ]);
    }
}
