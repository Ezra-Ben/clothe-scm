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
     
    Gate::define('create-tasks', function ($user) {
        return $user->role_id === 8;
    });

    Gate::define('allocate-tasks', function ($user) {
        return $user->role_id === 1;
    });

    Gate::define('employee', function ($user) {
        return $user->role_id === 9;
    });

Gate::define('admin', fn(User $user) => $user->role && $user->role->name === 'admin');
Gate::define('carrier', fn(User $user) => $user->role && $user->role->name === 'carrier');
    }
}
