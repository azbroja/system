<?php

namespace App\Providers;

use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();

        Gate::before(function (User $user, string $ability, array $arguments): ?bool {
            return $user->permissions()->whereName($ability)->exists();
        });

    }
}

// Gate::before(function (User $user, string $ability, array $arguments): ?bool {
//     return $user->permissions()->whereName($ability)->exists() ? null : false;
// });

// Gate::define('update_invoices', function (User $user, Invoice $invoice): ?bool {
//     return !$invoice->is_paid;
// });

// Gate::after(function (User $user, string $ability): ?bool {
//     return true;
// });
