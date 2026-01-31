<?php

namespace App\Traits;

use App\Models\Project;
use App\Models\ProjectRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Mrmarchone\LaravelAutoCrud\Helpers\SearchTermEscaper;


trait ProjectRequestFilterQuery
{
    public static function getQuery(): QueryBuilder
    {
        return QueryBuilder::for(ProjectRequest::class)
            ->allowedFilters([
                AllowedFilter::exact('status','project.status'),
                AllowedFilter::scope('search'),
            ])
            ->defaultSort('-created_at');
    }

    public function scopeSearch($query, $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        $likeTerm = SearchTermEscaper::escape($term);

        return $query->whereHas('project', function (Builder $q) use ($likeTerm) {
            $q->where(function (Builder $Q) use ($likeTerm) {
                $Q->whereRaw("title LIKE ? ESCAPE '!'", [$likeTerm])
                    ->orWhereRaw("description LIKE ? ESCAPE '!'", [$likeTerm])
                    ->orWhereRaw("tools LIKE ? ESCAPE '!'", [$likeTerm]);
            });
        });
    }
}
