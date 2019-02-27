<?php

namespace App\Providers;

use App\Models\Game;
use App\Models\Medal;
use App\Models\Phase;
use App\Models\User;
use App\Policies\GamePolicy;
use App\Policies\MedalPolicy;
use App\Policies\Phase\PhasePolicy;
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
        // Policiamento para CRUD de usuário.
        User::class => UserPolicy::class,
        // Policiamento para CRUD do jogo.
        Game::class => GamePolicy::class,
        // Policiamento para CRUD das fases do jogo
        Phase::class => PhasePolicy::class,
        // Policiamento para CRUD de medalhas.
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
        // Gate::define('game-phase-index', 'App\Policies\Phase\PhasePolicy@index');
        // Gate::define('game-phase-store', 'App\Policies\Phase\PhasePolicy@store');

        // Passport
        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(30));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
