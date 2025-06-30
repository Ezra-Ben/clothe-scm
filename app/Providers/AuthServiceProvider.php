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

	
        // Gate to manage suppliers (admins can manage)
        Gate::define('manage-suppliers', function (User $user) {
            return $user->hasRole('supplier_manager') || $user->hasRole('admin');
        });

        //Gate to view readonly pages(only supplier)
	Gate::define('view-readonly', function (User $user) {
            return $user->hasRole('supplier'); 
	});

    }
}
