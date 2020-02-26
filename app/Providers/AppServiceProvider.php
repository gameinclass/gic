<?php

namespace App\Providers;

use App\Models\Actor;
use App\Models\User;
use App\Observers\ActorObserver;
use App\Observers\UserObserver;
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
        Actor::observe(ActorObserver::class);
        User::observe(UserObserver::class);
    }
}
