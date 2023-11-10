<?php

declare(strict_types=1);

namespace App\Filters\Quiz;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByWritten
{
    public function handle(Builder $query, Closure $next)
    {
        $written = request()->input('written', 'none');

        $query = match ($written) {
            'true' => $query->whereHas('results', function ($query) {
                $query->where('user_id', request()->user()->id);
            }),
            'false' => $query->whereDoesntHave('results', function ($query) {
                $query->where('user_id', request()->user()->id);
            }),
            default => $query,
        };

        return $next($query);
    }
}
