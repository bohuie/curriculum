<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
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
        //
        Schema::defaultStringLength(191);

        Validator::extend('allowed_domain', function ($attribute, $value, $parameters, $validator) {
            return in_array(explode('@', $value)[1], ['ubc.ca', 'mail.ubc.ca', 'alumni.ubc.ca', 'student.ubc.ca']);
        }, 'Please Use A Valid UBC Domain Address When Entering Your Email');
    }
}
