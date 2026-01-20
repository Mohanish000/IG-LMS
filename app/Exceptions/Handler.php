<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle 419 Page Expired errors
        $this->renderable(function (TokenMismatchException $e, $request) {
            return redirect()->route('login')->with('error', 'Your session has expired. Please login again.');
        });

        // Handle 419 HTTP exceptions
        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                return redirect()->route('login')->with('error', 'Your session has expired. Please login again.');
            }
        });

        // Handle authentication errors
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to continue.');
        });

        // Catch all other exceptions (optional - be careful with this in production)
        $this->renderable(function (Throwable $e, $request) {
            if (!$request->expectsJson() && !app()->environment('local')) {
                // Log the actual error
                \Log::error('Error occurred: ' . $e->getMessage(), [
                    'exception' => $e,
                    'url' => $request->fullUrl(),
                ]);
                
                // Redirect to login for all errors in production
                return redirect()->route('login')->with('error', 'An error occurred. Please login again.');
            }
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => 'Unauthenticated.'], 401)
            : redirect()->guest(route('login'));
    }
}