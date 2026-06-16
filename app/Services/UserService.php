<?php

namespace App\Services;

use App\Data\UserData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Mrmarchone\LaravelAutoCrud\Helpers\MediaHelper;

class UserService
{
    public function update(User $user, UserData $userData): User
    {
        return DB::transaction(function () use ($user, $userData) {
            tap($user)->update($userData->onlyModelAttributes());

            if ($userData->avatar instanceof UploadedFile) {
                MediaHelper::updateMedia($userData->avatar, $user, 'primary-image');
            }

            return $user;
        });
    }
}
