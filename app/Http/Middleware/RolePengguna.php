<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RolePengguna
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $rolePengguna): Response
    {
        if (!Auth::check() || Auth::user()->rolePengguna !== $rolePengguna) {
            return redirect()->route('produk')->with('error', 'Access denied!');
        }

        return $next($request);
    }
}
