<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\RejectProjectRequestAction;
use App\Data\ProjectData;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequestRejectRequest;
use App\Http\Resources\ProjectRequestResource;
use App\Models\ProjectRequest;
use Illuminate\Http\Request;

class RejectProjectRequestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ProjectRequestRejectRequest $request,ProjectRequest $projectRequest,RejectProjectRequestAction $rejectProject): ProjectRequestResource
    {
        $projectRequest = $rejectProject->execute($projectRequest,ProjectData::from($request->validated()));

        return ProjectRequestResource::make($projectRequest->load(['project']))
            ->additional([
                'message' => ResponseMessages::UPDATED->message()
            ]);
    }
}
