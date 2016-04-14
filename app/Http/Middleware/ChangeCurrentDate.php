<?php

namespace App\Http\Middleware;

use Closure;
use Jenssegers\Date\Date;

class ChangeCurrentDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Date::setTestNow(new Date(config('gsa.current_date')));

        return $next($request);
    }
}
