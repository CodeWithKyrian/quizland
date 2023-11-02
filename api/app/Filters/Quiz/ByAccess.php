<?php

declare(strict_types=1);

namespace App\Filters\Quiz;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByAccess
{
    public function handle(Builder $query, Closure $next)
    {
        if (request()->user()->is_admin) {
            return $next($query);
        }

        // If the quiz belongs to a public program, then its public; if it belongs to a private program, then it's private and therefore only accessible to the creator and enrolled users.
        $query->where(function ($query) {
            $query->where('is_public', true)
                ->orWhere(function ($query) {
                    $query->where('is_public', false)
                        ->where(function ($query) {
                            $query->whereHas('program', function ($query) {
                                $query->where('is_public', true);
                            })
                                ->orWhere(function ($query) {
                                    $query->whereHas('program', function ($query) {
                                        $query->where('is_public', false);
                                    })
                                        ->where(function ($query) {
                                            $query->whereHas('program.enrolledUsers', function ($query) {
                                                $query->where('user_id', request()->user()->id);
                                            })
                                                ->orWhere('program.creator_id', request()->user()->id);
                                        });
                                });
                        });
                });
        });

        return $next($query);
    }
}
