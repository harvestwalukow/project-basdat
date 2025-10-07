<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('user_role') || !in_array(session('user_role'), ['admin', 'staff', 'owner'])) {
            return redirect()->route('signin')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return $next($request);
    }
}
