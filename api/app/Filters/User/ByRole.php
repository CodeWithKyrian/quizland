<?php

declare(strict_types=1);

namespace App\Filters\User;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByRole
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('role')) {
            return $next($query);
        }

        $role = request()->input('role');

        $query = match ($role) {
            'admin' => $query->where('is_admin', true),
            default => $query->where('is_admin', false),
        };

        return $next($query);
    }
}
