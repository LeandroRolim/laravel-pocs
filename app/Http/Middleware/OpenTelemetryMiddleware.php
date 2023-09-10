<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use OpenTelemetry\API\Globals;
use Symfony\Component\HttpFoundation\Response;

class OpenTelemetryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $span = Globals::tracerProvider()
            ->getTracer(config('app.name'))
            ->spanBuilder($request->getRequestUri())
            ->startSpan();
        $scope = $span->activate();
        $response = $next($request);
        $span->setAttribute('http.method', $request->method());
        $span->setAttribute('http.status', $response->getStatusCode());
        $span->setStatus($response->getStatusCode() < 400 ? 'OK' : 'ERROR');
        $span->end();
        $scope->detach();
        return $response;
    }
}
