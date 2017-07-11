<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class AccessTimetableMiddleware
 * @package App\Http\Middleware
 */
class AccessTimetableMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (access()->allow('view-schedule-management') && access()->allow('view-timetable-management')) {
            return $next($request);
        } else {
            return abort(404); // access denied
        }
    }
}
