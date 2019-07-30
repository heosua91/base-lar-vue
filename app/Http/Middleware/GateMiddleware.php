<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Exception;
use Closure;

class GateMiddleware
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
        $routeName = Route::currentRouteName();
        $routeNameParams = explode('.', $routeName);

        if (count($routeNameParams) <= 1)
            throw new Exception('Route name wrong!');

        if (!auth()->user()->can($routeNameParams[0])) {
            return $this->returnError($request);
        }

        return $next($request);
    }

    private function returnError($request) 
    {
        if ($request->wantsJson())
            return response()->json(['error' => 'Not permission!']);
        else
            abort(404);
    }
}
