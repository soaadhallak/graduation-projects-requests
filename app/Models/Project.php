<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'tools',
        'grade',
        'status',
        'supervisor_id',
        'admin_rejection_reason'
    ];

    public function supervisor():BelongsTo{
        return $this->belongsTo(User::class,'supervisor_id');
    }
}
