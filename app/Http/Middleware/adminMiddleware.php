<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('AdminMiddleware initialized', json_encode($next));
        if (Auth::user()->email !== 'admin@lms.com') {
            return redirect('/dashboard')->with('error', 'Unauthorized access!');
        }

        return $next($request);
    }
}