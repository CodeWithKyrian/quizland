<?php

declare(strict_types=1);

namespace App\Traits;

use Spatie\QueryBuilder\QueryBuilder;

trait UsesQueryBuilder
{
    public static function queryBuilder(): QueryBuilder
    {
        return QueryBuilder::for(self::class);
    }
}
