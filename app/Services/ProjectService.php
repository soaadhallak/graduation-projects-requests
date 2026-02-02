<?php
namespace App\Services;

use App\Data\ProjectData;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Mrmarchone\LaravelAutoCrud\Helpers\MediaHelper;

class ProjectService
{

    public function store(ProjectData $data):Project
    {
        return DB::transaction(function() use ($data){
            $project = Project::create($data->onlyModelAttributes());

            if(!empty($data->files)){
                MediaHelper::uploadMedia($data->files,$project,'project_documents');
            }

            return $project;
        });
    }

    public function update(ProjectData $data,Project $project):Project
    {
        return DB::transaction(function() use ($data,$project){
            tap($project)->update($data->onlyModelAttributes());

            if(!empty($data->files)){
                MediaHelper::updateMedia($data->files,$project,'project_documents');
            }

            return $project;
        });
    }
}
