<?php

declare(strict_types=1);

namespace App\Filters\Program;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByStatus
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('status')) return $next($query);

        $status = request()->input('status');

        $query = match ($status) {
            'all' => $query,
            'unpublished' => $query->where('is_published', false),
            default => $query->where('is_published', true),
        };

        return $next($query);
    }
}
