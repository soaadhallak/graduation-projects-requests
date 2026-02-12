<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Actions\StatisticsAction;
use App\Enums\ResponseMessages;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StatisticsAction $statisticsService)
    {
        $data = $statisticsService->execute();

        return response()->json([
            'data' => $data,
            'message' => ResponseMessages::RETRIEVED->message()
        ]);
    }
}
