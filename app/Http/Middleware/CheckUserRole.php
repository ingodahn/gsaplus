<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $allowed = false;

        foreach ($roles as $role) {
            if ($request->user()->type === $role) {
                $allowed = true;
                break;
            }
        }

        if ($allowed) {
            return $next($request);
        } else {
            return redirect('/Home');
        }
    }
}
