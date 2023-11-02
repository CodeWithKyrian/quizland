<?php

declare(strict_types=1);

namespace App\Filters\Quiz;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByWritten
{
    public function handle(Builder $query, Closure $next)
    {
        $written = request()->input('written', 'all');

        $query = match ($written) {
            'written' => $query->whereHas('results', function ($query) {
                $query->where('user_id', auth('api')->id());
            }),
            'not_written' => $query->whereDoesntHave('results', function ($query) {
                $query->where('user_id', auth('api')->id());
            }),
            default => $query,
        };

        return $next($query);
    }
}
