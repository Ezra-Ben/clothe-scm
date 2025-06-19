<?php
use Illuminate\Support\Facades\Gate;

public function boot()
{
    $this->registerPolicies();

    Gate::define('view-admin-supplier-dashboard', function ($user) {
        return $user->role === 'admin';
    });
     Gate::define('manage_performance', function ($user) {
        return $user-> isAdmin();
    });
    Gate::define('manage-suppliers', function ($user) {
        return $user-> isAdmin();
    });
     
    Gate::define('upload-contract', function ($user) {
        return $user->role === 'admin';
    });

    Gate::define('view-contract', function ($user) {
        return in_array($user->role, ['admin', 'supplier']);
    });
}
