<?php

declare(strict_types=1);

namespace App\Filters\Program;

use App\Models\Program;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByAccess
{
    public function handle(Builder $query, Closure $next)
    {
        if (request()->user()->is_admin) {
            return $next($query);
        }

        $query->where('is_public', true)
            ->orWhere(function (Builder $query) {
                $query->whereRelation('enrolledUsers', 'user_id', request()->user()->id)
                    ->orWhere('creator_id', request()->user()->id);
            });

        return $next($query);
    }
}
