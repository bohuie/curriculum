<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Invitatation extends Middleware
{
    // User will be redirected to regiration page
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return redirect('register');
        }
    }
}
