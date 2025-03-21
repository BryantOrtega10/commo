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
        $admin_permissions = [
            'home',
            'policies',
            'customers',
            'agents',
            'products',
            'reports',
            'commissions',
            'users',
            'client-sources',
            'counties',
            'enrollment-methods',
            'policy-agent-number-types',
            'policy-member-types',
            'policy-status',
            'relationships',
            'genders',
            'marital-status',
            'regions',
            'states',
            'suffixes',
            'customer-status',
            'phases',
            'legal-basis',
            'registration-sources',
            'agencies',
            'agency-codes',
            'agent-contract-types',
            'agent-status',
            'agent-titles',
            'sales-regions',
            'business-segments',
            'business-types',
            'carriers',
            'plan-types',
            'product-tiers',
            'product-types'
        ];
        
        foreach ($admin_permissions as $admin_permission) {
            Gate::define($admin_permission, function (User $user) {
                return (strtolower($user->role) == 'admin');
            });
        }
    }
}
