<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapName(SnakeCaseMapper::class)]
class PostData extends Data
{
    public function __construct(
        public string                $title,
        public string                $slug,
        public string                $userId,
        #[WithoutValidation]
        public ?string               $thumbnail,
        public string                $description,
        public string                $body,
        public int                   $category_id,
        #[WithoutValidation]
        public CategoryData|Optional $category,
        #[WithoutValidation]
        public bool|Optional         $isPublished,
        #[WithoutValidation]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'F d, Y')]
        public ?Carbon               $publishedAt
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'category_id' => ['required', 'integer'],
        ];
    }
}
