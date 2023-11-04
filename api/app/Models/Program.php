<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\UsesQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory, Filterable, UsesQueryBuilder;

    protected $fillable = [
        'creator_id',
        'title',
        'slug',
        'description',
        'is_public',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::saving(function ($program) {
            $program->slug = str($program->title)->slug();
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function enrolledUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(ProgramInvitation::class);
    }
}
