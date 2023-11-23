<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CourseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (! auth()->user()->courses->contains($request->route('course'))) {
            return redirect('courses');
        }

        return $next($request);
    }
}
