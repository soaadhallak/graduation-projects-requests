<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Traits\ProjectFilterQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Mrmarchone\LaravelAutoCrud\Traits\HasMediaConversions;


class Project extends Model implements HasMedia
{
    use HasMediaConversions,ProjectFilterQuery;

    protected $fillable = [
        'title',
        'description',
        'tools',
        'grade',
        'status',
        'supervisor_id',
        'admin_rejection_reason'
    ];

    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class
        ];
    }

    public function supervisor():BelongsTo{
        return $this->belongsTo(User::class,'supervisor_id');
    }

    public function projectRequests():HasMany{
        return $this->hasMany(ProjectRequest::class);
    }
}
