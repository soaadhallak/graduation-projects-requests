<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectRequest extends Model
{
    protected $fillable = [
        'team_id',
        'project_id',
        'is_looking_for_members',
        'max_number'
    ];

    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }

    public function project():BelongsTo{
        return $this->belongsTo(Project::class);
    }

}
