<?php

namespace App\Providers;

use App\Models\Emi;
use App\Models\Member;
use App\Observers\EmiObserver;
use App\Observers\MemberObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Member::observe(MemberObserver::class);
        Emi::observe(EmiObserver::class);
        Gate::before(function ($user, $ability) {
        return $user->hasRole('admin') ? true : null;
    });

    }
}
