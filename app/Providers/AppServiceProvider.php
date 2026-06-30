<?php

namespace App\Providers;

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
        $this->isProduction();
        $this->setSuperAdmin();
    }

    private function isProduction(): void
    {
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }

    private function setSuperAdmin(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }

}
