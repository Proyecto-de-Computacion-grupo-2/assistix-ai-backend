<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && auth()->user()->admin) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
