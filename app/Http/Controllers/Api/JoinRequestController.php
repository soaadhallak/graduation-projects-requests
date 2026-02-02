<?php

namespace App\Http\Controllers\Api;

use App\Data\JoinRequestData;
use App\Http\Controllers\Controller;
use App\Http\Requests\JoinRequestStoreRequest;
use App\Http\Resources\JoinRequestResource;
use App\Models\TeamJoinRequest;
use Illuminate\Http\Request;
use App\Services\JoinRequestService;
use Illuminate\Support\Facades\Auth;
use Mrmarchone\LaravelAutoCrud\Enums\ResponseMessages;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JoinRequestController extends Controller
{
    public function __construct(
        public JoinRequestService $joinRequestService
    )
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny',TeamJoinRequest::class);

        $joinRequests = Auth::user()->team->joinRequests()
            ->with(['user', 'team','user.media','user.student'])
            ->get();

        return JoinRequestResource::collection($joinRequests)
            ->additional([
                'message' => ResponseMessages::RETRIEVED->message()
            ]);    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JoinRequestStoreRequest $request): JsonResponse
    {
        $joinRequest = $this->joinRequestService->store(JoinRequestData::from($request->validated()),Auth::user());

        return JoinRequestResource::make($joinRequest->load(['user','user.media','user.student','team']))
            ->additional([
                'message' => ResponseMessages::CREATED->message()
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
