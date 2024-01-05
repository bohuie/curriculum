<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Closure;
use Illuminate\Http\Request;

class CourseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (! auth()->user()->courses->contains($request->route('course'))) {
            return redirect('courses');
        }

        return $next($request);
    }
}
