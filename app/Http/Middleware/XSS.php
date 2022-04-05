<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Most likely should be removed. I have commented this out under the Kernel middleware group.
// Laravel has built in XSS protection built in and this is redundant.
// if the user uses '<>' in any of the input fields then it will be removed, which is not ideal.
class XSS {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $userInput = $request->all();
        array_walk_recursive($userInput, function (&$userInput) {
            $userInput = strip_tags($userInput);
        });
        
        $request->merge($userInput);
        return $next($request);
    }
}
