<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckFingerprint
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $fingerprint = $request->header('X-Fingerprint');

        if ($user && $user->fingerprint && $user->fingerprint !== $fingerprint) {
            return response()->json(['message' => 'Fingerprint mismatch.'], 403);
        }

        return $next($request);
    }
}
