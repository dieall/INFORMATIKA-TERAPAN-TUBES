<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SecurityLog;

class LogSecurityEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check()) {
            SecurityLog::create([
                'user_id' => auth()->id(),
                'event_type' => 'page_access',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'severity' => 'low',
                'description' => 'User accessed: ' . $request->path(),
                'metadata' => [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                ],
                'status' => 'success',
            ]);
        }

        return $response;
    }
}
