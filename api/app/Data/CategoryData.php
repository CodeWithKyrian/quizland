<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapName(SnakeCaseMapper::class)]
class CategoryData extends Data
{
    public function __construct(
        public string                  $name,
        public string                  $slug,
        #[DataCollectionOf(PostData::class)]
        public DataCollection|Optional $posts,
        public int|Optional            $posts_count
    )
    {
    }
}
