<?php

declare(strict_types=1);

namespace App\Filters\Program;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByStatus
{
    public function handle(Builder $query, Closure $next)
    {
        $status = request()->input('status', 'all');

        $query = match ($status) {
            'published' => $query->where('is_published', true),
            'unpublished' => $query->where('is_published', false),
            default => $query,
        };

        return $next($query);
    }
}
