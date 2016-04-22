<?php

namespace App\Http\Middleware;

use App\TestSetting;

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
        $settings = TestSetting::first();

        if ($settings && $settings->test_date) {
            Date::setTestNow($settings->test_date);
        }

        return $next($request);
    }
}
