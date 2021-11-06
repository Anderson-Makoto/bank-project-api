<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
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
        $isAdmin = $this->getUserRole();

        if ($isAdmin) {
            return $next($request);
        } else {
            return response()->json([
                "data" => "You don't have admin's permissions"
            ], 401);
        }
    }

    private function getUserRole()
    {
        return auth()->user()->is_admin;
    }
}
