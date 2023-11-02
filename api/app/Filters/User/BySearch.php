<?php

declare(strict_types=1);

namespace App\Filters\User;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class BySearch
{
    public function handle(Builder $query, Closure $next)
    {
        $search = request()->input('search', '');

        $query = $query
            ->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");

        return $next($query);
    }
}
