<?php

declare(strict_types=1);

namespace App\Filters\Quiz;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByProgramStatus
{
    public function handle(Builder $query, Closure $next)
    {
        if (request()->user()->is_admin) {
            return $next($query);
        }

        $query->whereHas('program', function ($query) {
            $query
                ->where('is_published', true)
                ->orWhere('creator_id', request()->user()->id);
        });

        return $next($query);
    }
}
