<?php

declare(strict_types=1);

namespace App\Filters\User;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByRole
{
    public function handle(Builder $query, Closure $next)
    {
        $role = request()->input('role', 'all');

        $query = match ($role) {
            'admin' => $query->where('is_admin', true),
            'user' => $query->where('is_admin', false),
            default => $query,
        };

        return $next($query);
    }
}
