<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Actor;
use App\Models\Medal;
use Laravel\Passport\Passport;
use App\Observers\UserObserver;
use App\Observers\ActorObserver;
use App\Observers\Medal\MedalObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Token Lifetimes
        Passport::refreshTokensExpireIn(now()->addDays(30));

        //
        Actor::observe(ActorObserver::class);
        User::observe(UserObserver::class);

        // Medal Observer
        Medal::observe(MedalObserver::class);
    }
}
