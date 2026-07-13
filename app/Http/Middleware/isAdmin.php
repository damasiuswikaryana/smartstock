<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->roles[0]->name == 'masteradmin') {
            return $next($request);
        } else {
            return $next($request);
        }
    }
}
