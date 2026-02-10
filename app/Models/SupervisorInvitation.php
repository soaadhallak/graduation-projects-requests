<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisorInvitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'accepted_at'
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return !is_null($this->accepted_at);
    }
    
}
