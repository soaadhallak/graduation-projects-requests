<?php

namespace App\Actions;

use App\Data\SupervisorInvitationData;
use App\Mail\SupervisorInvitationMail;
use App\Models\SupervisorInvitation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InviteSupervisorAction
{
    public function execute(SupervisorInvitationData $data): SupervisorInvitation
    {
        return DB::transaction(function () use ($data) {
            $invitation = SupervisorInvitation::create($data->onlyModelAttributes());

            Mail::to($data->email)->send(new SupervisorInvitationMail($invitation));

            return $invitation;
        });
    }
}
