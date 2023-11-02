<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quiz extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'program_id',
        'title',
        'description',
        'slug',
        'duration',
        'base_score',
        'pass_mark',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public $timestamps = false;

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(Result::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
