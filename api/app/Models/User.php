<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Filterable, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_creator',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_creator' => 'boolean',
    ];

    public function createdPrograms(): HasMany
    {
        return $this->hasMany(Program::class, 'creator_id');
    }

    public function enrolledPrograms(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'enrollments');
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function programInvitations(): HasMany
    {
        return $this->hasMany(ProgramInvitation::class);
    }

    public function isEnrolledIn(Program $program): bool
    {
        return $this->enrolledPrograms->contains($program);
    }

    public function enroll(Program $program): void
    {
        $this->enrolledPrograms()->syncWithoutDetaching([$program->id]);
    }

    public function withdraw(Program $program): void
    {
        $this->enrolledPrograms()->detach($program);
    }
}
