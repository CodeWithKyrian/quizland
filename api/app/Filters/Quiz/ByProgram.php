<?php

declare(strict_types=1);

namespace App\Filters\Quiz;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByProgram
{
    public function handle(Builder $query, Closure $next)
    {
        if (request()->has('program_id')) {
            $programId = request()->input('program_id');
            $query->where('program_id', $programId);
        }

        return $next($query);
    }
}
