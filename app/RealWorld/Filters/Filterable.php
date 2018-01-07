<?php

namespace App\RealWorld\Filters;

use Jenssegers\Mongodb\Eloquent\Builder;

trait Filterable
{
    /**
     * Scope a query to apply given filter.
     *
     * @param \Jenssegers\Mongodb\Eloquent\Builder $builder
     * @param Filter $filter
     * @return \Jenssegers\Mongodb\Eloquent\Builder
     */
    public function scopeFilter(Builder $builder, Filter $filter)
    {
        return $filter->apply($builder);
    }
}
