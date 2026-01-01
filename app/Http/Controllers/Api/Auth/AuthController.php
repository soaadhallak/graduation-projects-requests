<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterNewUserAction;
use App\Data\StudentDetailsData;
use App\Data\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request,RegisterNewUserAction $registerNewUserAction):JsonResponse
    {
        $result=$registerNewUserAction->execute(UserData::from($request->validated()),StudentDetailsData::from($request->validated()));
        $user = $result['user'];
        $token = $result['token'];

        return UserResource::make($user->load(['media','studentDetail','roles']))
            ->additional([
                'message'=>'Created User Successfully',
                'token'=>$token
            ])->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request,LoginUserAction $loginUserAction):UserResource
    {
        $result=$loginUserAction->execute(UserData::from($request->validated()));
        $user=$result['user'];
        $token=$result['token'];

        return UserResource::make($user->load(['media','studentDetail','roles']))
            ->additional([
                'message'=>'Login User Successfully',
                'token'=>$token
            ]);
    }

    public function logout(Request $request)
    {
        $user=$request->user();
        $user->currentAccessToken()->delete();

        return UserResource::make($user->load(['media','studentDetail','roles']))
            ->additional([
                'message'=>'Logout User Successfully',
            ]);
    }
}
