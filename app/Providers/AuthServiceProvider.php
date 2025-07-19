<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function (User $user) {
            return $user->hasRole('admin');
        });

	    Gate::define('supplier', function (User $user) {
            return $user->hasRole('supplier'); 
	    });
	    
        Gate::define('manage-suppliers', function (User $user) {
            return $user->hasRole('supplier_manager') || $user->hasRole('admin');
        });

	    Gate::define('manage-products', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('product_manager');
        });

        Gate::define('manage-inventory', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('inventory_manager');
        });

        Gate::define('manage-procurement', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('procurement_manager');
        });

        Gate::define('manage-production', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('production_manager');
        });
    }
}
