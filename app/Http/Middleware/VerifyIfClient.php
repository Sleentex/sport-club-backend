<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use Closure;
use Illuminate\Http\Request;

class VerifyIfClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->role != UserRoles::CLIENT) abort(403, "You don't have permissions");

        return $next($request);
    }
}
