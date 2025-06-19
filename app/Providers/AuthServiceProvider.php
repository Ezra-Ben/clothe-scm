<?php
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-suppliers', function ($user) {
            return $user->isAdmin();
        });
    }
}