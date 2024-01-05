<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        // Gate::define('manage-users', function($user){
        //     return $user->hasAnyRole(['administrator','user']);
        // });

        // Gate::define('edit-users', function($user){
        //     return $user->hasRole('administrator');
        // });

        // Gate::define('isAdmin', function($user){
        //     return $user->hasRole('administrator');
        // });

        Gate::define('admin-privilege', function ($user) {
            return $user->hasRole('administrator');
        });
    }
}
