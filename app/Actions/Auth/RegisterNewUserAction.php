<?php
namespace App\Actions\Auth;

use App\Data\StudentData;
use App\Data\UserData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mrmarchone\LaravelAutoCrud\Helpers\MediaHelper;

class RegisterNewUserAction
{
    public function execute(UserData $userData,StudentData $studentData):array
    {
        return DB::transaction(static function () use ($userData,$studentData)
        {
            Log::info(['user',$userData]);
            $user=User::create($userData->onlyModelAttributes());

            if($userData->avatar instanceof UploadedFile){
                MediaHelper::uploadMedia($userData->avatar,$user,'primary-image');
            }

            $user->assignRole('student');
            $token=$user->createToken('user-token')->plainTextToken;

            $user->student()->create($studentData->onlyModelAttributes());

            return[
                'user'=>$user,
                'token'=>$token
            ];
        });
    }
}
