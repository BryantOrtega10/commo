<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

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

        if ($this->app->environment('production')) {
            // Forzar HTTPS en todas las URLs generadas
            URL::forceScheme('https');
        }

        HeadingRowFormatter::extend('custom', function ($heading) {
            $heading = strtolower($heading);
            $heading = trim($heading);
            $heading = str_replace(' ', '_', $heading);
            $heading = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $heading);
            $heading = preg_replace('/[^a-z0-9_]/', '', $heading);
            return $heading;
        });
        HeadingRowFormatter::default('custom');


        $agent_permissions = [
            'leads',
            'my-statements'
        ];

        $supervisor_permissions = [
            'supervisor',
            'logs'
        ];

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
            'contract-types',
            'agent-status',
            'agent-titles',
            'sales-regions',
            'business-segments',
            'business-types',
            'carriers',
            'plan-types',
            'product-tiers',
            'product-types',
            'admin-fees',
            'compensation-types'
        ];

        foreach ($admin_permissions as $admin_permission) {
            Gate::define($admin_permission, function (User $user) {
                return (strtolower($user->role) == 'admin' || strtolower($user->role) == 'superadmin');
            });
        }

        foreach ($agent_permissions as $agent_permission) {
            Gate::define($agent_permission, function (User $user) {
                return (strtolower($user->role) == 'agent');
            });
        }

        foreach ($supervisor_permissions as $supervisor_permission) {
            Gate::define($supervisor_permission, function (User $user) {
                return (strtolower($user->role) == 'supervisor');
            });
        }
    }
}
