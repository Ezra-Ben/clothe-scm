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
     
    Gate::define('user', function(User $user) {
        return  $user->role ->hasRole('user');});


        Gate::define('admin', fn (User $user) => $user->role === 'admin');

        // Gate for carrier
        Gate::define('carrier', fn (User $user) => $user->role === 'carrier');

        // Gate for standard user
        Gate::define('user', fn (User $user) => $user->role === 'user');
    }
}
