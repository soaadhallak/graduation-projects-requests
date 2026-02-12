<?php

namespace App\Actions;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticsAction
{
    public function execute(): array
    {
        return [
            'projectsMetrics'     => $this->getProjectStatusCounts(),
            'supervisorsWorkload' => $this->getSupervisorsMetrics(),
            'yearlyPerformance'   => $this->getYearlyGradesAverage(),
        ];
    }

    private function getProjectStatusCounts(): array
    {
        $counts = Project::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'active'   => $counts[ProjectStatus::ACTIVE->value] ?? 0,
            'rejected' => $counts[ProjectStatus::REJECTED->value] ?? 0,
            'pending'  => $counts[ProjectStatus::PENDING->value] ?? 0,
        ];
    }

    private function getSupervisorsMetrics(): array
    {
        $supervisors = User::role('supervisor')
            ->withCount('projects')
            ->get(['id', 'name']);

        return [
            'total_count' => $supervisors->count(),
            'distribution' => $supervisors,
        ];
    }

    private function getYearlyGradesAverage(): array
    {
        return Project::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('ROUND(AVG(grade), 2) as average')
            )
            ->whereNotNull('grade')
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year')
            ->get()
            ->toArray();
    }
}
