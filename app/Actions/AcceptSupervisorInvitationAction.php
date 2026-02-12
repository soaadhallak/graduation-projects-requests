<?php

namespace App\Actions;

use App\Data\UserData;
use App\Models\SupervisorInvitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Mrmarchone\LaravelAutoCrud\Helpers\MediaHelper;

class AcceptSupervisorInvitationAction
{

    public function execute(UserData $data,string $token): array
    {
        return DB::transaction(function () use ($data,$token) {
            $user = User::create($data->onlyModelAttributes());

            if($data->avatar instanceof UploadedFile){
                MediaHelper::uploadMedia($data->avatar,$user,'primary-image');
            }

            $invitation = SupervisorInvitation::where('email', $data->email)
                ->where('token',$token)
                ->whereNull('accepted_at')
                ->first();

            $invitation->update(['accepted_at' => now()]);

            $user->assignRole('supervisor');
            $token=$user->createToken('user-token')->plainTextToken;

            return[
                'user'=>$user,
                'token'=>$token
            ];;
        });
    }
}
