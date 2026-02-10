<?php

namespace App\Http\Controllers\Api;

use App\Actions\AcceptSupervisorInvitationAction;
use App\Data\UserData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupervisorStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $supervisors = User::role('supervisor')
            ->with(['media','permissions','roles'])
            ->get();

        return UserResource::collection($supervisors)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupervisorStoreRequest $request,AcceptSupervisorInvitationAction $acceptSupervisor):JsonResponse
    {
        $supervisor = $acceptSupervisor->execute(UserData::from($request->validated()),$request->token);

        return UserResource::make($supervisor['user']->load(['media','roles','permissions']))
            ->additional([
                'message' => ResponseMessages::CREATED->message(),
                'token'=>$supervisor['token']
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        return UserResource::make($user->load(['media','roles','permissions']))
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): UserResource
    {
        $user->delete();

        return UserResource::make($user->load(['media','roles','permissions']))
            ->additional([
                'message' => ResponseMessages::DELETED->message()
            ]);
    }
}
