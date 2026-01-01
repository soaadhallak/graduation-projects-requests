<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = [
        'name',
        'leader_id'
    ];

    public function leader():BelongsTo{
        return $this->belongsTo(User::class,'leader_id');
    }

    public function studentDetails():HasMany{
        return $this->hasMany(StudentDetail::class);
    }

    public function projectRequests():HasMany
    {
        return $this->hasMany(ProjectRequest::class);
    }
}
