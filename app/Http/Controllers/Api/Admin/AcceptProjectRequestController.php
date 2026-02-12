<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\AcceptProjectRequestAction;
use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequestAcceptedRequest;
use App\Http\Resources\ProjectRequestResource;
use App\Models\ProjectRequest;
use Illuminate\Http\Request;

class AcceptProjectRequestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ProjectRequest $projectRequest,AcceptProjectRequestAction $acceptProject,ProjectRequestAcceptedRequest $request):ProjectRequestResource
    {
        $projectRequest = $acceptProject->execute($projectRequest);

        return ProjectRequestResource::make($projectRequest->load(['project']))
            ->additional([
                'message' => ResponseMessages::UPDATED->message()
            ]);
    }
}
