<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Program;
use App\Models\Quiz;
use App\Models\User;
use App\Policies\ProgramPolicy;
use App\Policies\QuizPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        Program::class => ProgramPolicy::class,
        Quiz::class => QuizPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
