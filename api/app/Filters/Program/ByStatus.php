<?php

declare(strict_types=1);

namespace App\Filters\Program;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByStatus
{
    public function handle(Builder $query, Closure $next)
    {
        $status = request()->input('published');

        $query = match ($status) {
            'true' => $query->where('is_published', true),
            'false' => $query->where('is_published', false),
            default => $query,
        };

        return $next($query);
    }
}
