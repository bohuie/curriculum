<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        Validator::extend('allowed_domain', function($attribute, $value, $parameters, $validator) {
            return in_array(explode('@', $value)[1], ['ubc.ca', 'mail.ubc.ca', 'alumni.ubc.ca', 'student.ubc.ca']);
        }, 'Please Use A Valid UBC Domain Address When Entering Your Email');
    }
}
