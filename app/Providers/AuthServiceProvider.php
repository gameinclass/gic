<?php

namespace App\Providers;

use App\Models\Game;
use App\Models\Medal;
use App\Models\Phase;
use App\Models\Player;
use App\Models\User;
use App\Policies\GamePolicy;
use App\Policies\MedalPolicy;
use App\Policies\Phase\PhasePolicy;
use App\Policies\Player\PlayerPolicy;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
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
        // Policiamento para CRUD de jogadores do jogo.
        Player::class => PlayerPolicy::class,
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
        // Resource: Game / Medal
        Gate::define('game-medal-store', 'App\Policies\Game\Medal\MedalPolicy@store');
        Gate::define('game-medal-destroy', 'App\Policies\Game\Medal\MedalPolicy@destroy');

        // Passport
        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(30));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
