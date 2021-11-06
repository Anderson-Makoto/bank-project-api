<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsCustomer
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
        $isCustomer = $this->getUserRole();

        if ($isCustomer) {
            return $next($request);
        } else {
            return response()->json([
                "data" => "You don't have customer's permissions"
            ], 401);
        }
    }

    private function getUserRole()
    {
        return !(auth()->user()->is_admin);
    }
}
