<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamInvitation extends Model
{
    protected $fillable=[
        'team_id',
        'token',
        'email',
        'status',
        'expires_at'
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function team():BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
