<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Pipeline;

trait Filterable
{
    /**
     * Filter a query builder using a pipeline of filters.
     */
    public function scopeFilter($query, array $filters): mixed
    {
        return Pipeline::send($query)
            ->through($filters)
            ->thenReturn();
    }
}
