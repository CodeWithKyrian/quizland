<?php

declare(strict_types=1);

namespace App\Filters\Program;

use App\Models\Program;
use Closure;
use Illuminate\Database\Eloquent\Builder;


class BySearch
{

    public function handle(Builder $query, Closure $next): mixed
    {
        $search = request()->input('search', '');

        if ($search) {
            $query = $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        return $next($query);
    }
}
