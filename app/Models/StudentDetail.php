<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class StudentDetail extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    
    protected $fillable = [
        'university_number',
        'skills',
        'user_id',
        'major_id',
        'team_id'
    ];


    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function major():BelongsTo{
        return $this->belongsTo(Major::class);
    }

}
