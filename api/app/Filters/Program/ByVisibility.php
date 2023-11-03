<?php

declare(strict_types=1);

namespace App\Filters\Program;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByVisibility
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('visibility')) return $next($query);

        $visibility = request()->input('visibility');

        $query = match ($visibility) {
            'private' => $query->where('is_public', false),
            'all' => $query,
            default => $query->where('is_public', true),
        };

        return $next($query);
    }
}
