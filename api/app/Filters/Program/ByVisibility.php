<?php

declare(strict_types=1);

namespace App\Filters\Program;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByVisibility
{
    public function handle(Builder $query, Closure $next)
    {
        $visibility = request()->input('visibility', 'all');

        $query = match ($visibility) {
            'private' => $query->where('is_public', false),
            'public' => $query->where('is_public', true),
            default => $query,
        };

        return $next($query);
    }
}
