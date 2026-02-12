<?php

namespace App\Traits;

use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Mrmarchone\LaravelAutoCrud\Helpers\SearchTermEscaper;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

trait ProjectFilterQuery
{
    public static function getQuery(): QueryBuilder
    {
        return QueryBuilder::for(Project::class)
            ->allowedFilters([
                AllowedFilter::exact('supervisorId', 'supervisor_id'),
                AllowedFilter::scope('fromTo', 'gradeBetween'),
                AllowedFilter::scope('search')
            ])
            ->defaultSort('-created_at');
    }

    public function scopeGradeBetween($query, ...$payload): Builder
    {
        $grades = collect($payload)->flatten()->all();

        if (count($grades) < 2) {
            return $query;
        }
        
        $from = (float) $grades[0];
        $to = (float) $grades[1];

        return $query->whereBetween('grade', [$from, $to]);
    }

    public function scopeSearch($query, $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        $likeTerm = SearchTermEscaper::escape($term);

        return $query->where(function (Builder $q) use ($likeTerm) {
            $q->whereRaw("title LIKE ? ESCAPE '!'", [$likeTerm])
                ->orWhereRaw("description LIKE ? ESCAPE '!'", [$likeTerm])
                ->orWhereRaw("tools LIKE ? ESCAPE '!'", [$likeTerm]);
        });
    }
}
