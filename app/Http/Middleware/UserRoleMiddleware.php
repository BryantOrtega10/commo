<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        $roles = explode("|", $roles);
        if (Auth::check()) {
            foreach ($roles as $role) {
                if (Auth::user()->role != null && strtolower(Auth::user()->role) == strtolower($role))
                    return $next($request);
            }
        }
        switch(strtolower(Auth::user()->role)){
            case 'admin':
                return redirect(route('dashboard'));
                break;
            default:
                return redirect(route('dashboard'));
                break;
        }
        return redirect('/');
    }
}
