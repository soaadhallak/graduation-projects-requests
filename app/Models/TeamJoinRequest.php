<?php

namespace App\Models;

use App\Enums\JoinRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamJoinRequest extends Model
{
    protected $fillable = [
        'user_id',
        'team_id',
        'status',
        'project_request_id'
    ];

    protected function casts(): array
    {
        return [
            'status' => JoinRequestStatus::class
        ];
    }

    public function team():BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projectRequest():BelongsTo
    {
        return $this->belongsTo(ProjectRequest::class);
    }
}
