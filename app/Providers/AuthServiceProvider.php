<?php

namespace App\Providers;

use App\Models\Game;
use App\Models\Medal;
use App\Models\User;
use App\Policies\GamePolicy;
use App\Policies\MedalPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Game::class => GamePolicy::class,
        Medal::class => MedalPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gates
        // Resource: Game / Phase
        Gate::define('game-phase-index', 'App\Policies\Phase\PhasePolicy@index');
        Gate::define('game-phase-store', 'App\Policies\Phase\PhasePolicy@store');

        // Passport
        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(30));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
