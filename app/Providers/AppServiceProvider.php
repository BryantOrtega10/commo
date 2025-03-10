<?php

namespace App\Providers;

use App\Models\User;
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
        $admin_permissions = ['home','policies','customers','agents','products','reports','commissions','users'];
        foreach($admin_permissions as $admin_permission){
            Gate::define($admin_permission, function (User $user) {
                return (strtolower($user->rol) == 'admin');
            });
        }
        
    }
}
