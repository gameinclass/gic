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
        Phase::class => PhasePolicy::class,
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
        // Passport
        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(30));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
